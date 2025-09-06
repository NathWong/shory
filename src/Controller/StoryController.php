<?php

namespace App\Controller;

use App\Entity\ModerationMessage;
use App\Entity\Story;
use App\Enum\StoryStatus;
use App\Form\ModerationMessageType;
use App\Form\NewStoryType;
use App\Repository\ModerationMessageRepository;
use App\Repository\StoryRepository;
use App\Security\Voter\StoryVoter;
use App\Service\StoryTemplateManager;
use App\Trait\ProfiledUserTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/story')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class StoryController extends AbstractController
{
    use ProfiledUserTrait;

    #[Route('/new', name: 'app_story_new', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $story = new Story();
        $form = $this->createForm(NewStoryType::class, $story);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Initialize story values
            $story
                ->setOwner($this->getUserProfile())
                ->setStoryStatus(StoryStatus::DRAFT->value)
            ;

            $entityManager->persist($story);
            $entityManager->flush();

            return $this->redirectToRoute('app_story_show', ['id' => $story->getId()]);
        }

        return $this->render('story/new.html.twig', [
            'form' => $form,
            'edit' => false,
            'story' => $story,
        ]);
    }

    #[Route(
        '/index',
        name: 'app_story_index',
        methods: ['GET'],
    )]
    public function index(
        StoryRepository $storyRepository,
        StoryTemplateManager $storyTemplateManager,
        #[MapQueryParameter]?string $q = null,
        #[MapQueryParameter]string $sort = 'updatedAt',
    ): Response {
        $stories = $storyRepository->findByFilters($this->getUserProfile(), $q, $sort);

        return $this->render('story/index.html.twig', [
            'stories' => $stories,
            'searchTerm' => $q,
            'sortBy' => $sort,
            'templateManager' => $storyTemplateManager,
            ]);
    }

    #[Route('/show/{id}', name: 'app_story_show', methods: ['GET'])]
    #[IsGranted(StoryVoter::VIEW, subject: 'story')]
    public function show(
        Story $story,
        StoryTemplateManager $templateManager,
        ModerationMessageRepository $moderationMessageRepository,
        Security $security
    ): Response {
        $chapters = $story->getChapters();
        $form = $this->createFormBuilder()->getForm();

        $moderationMessages = [];
        // Only show moderation messages to the owner, or to a moderator/admin
        if ($this->isGranted('ROLE_MODERATOR') || $story->getOwner()->getAccount() === $security->getUser()) {
            $moderationMessages = $moderationMessageRepository->findBy(['story' => $story], ['createdAt' => 'DESC']);
        }

        return $this->render('story/show.html.twig', [
            'form' => $form,
            'story' => $story,
            'chapters' => $chapters,
            'template' => $templateManager->getTemplate($story->getTemplate()),
            'moderationMessages' => $moderationMessages,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_story_edit', methods: ['GET', 'POST'])]
    #[IsGranted(StoryVoter::EDIT, subject: 'story')]
    public function edit(
        Story $story,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(NewStoryType::class, $story);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_story_show', ['id' => $story->getId()]);
        }

        return $this->render('story/new.html.twig', [
            'story' => $story,
            'form' => $form,
            'edit' => true,
        ]);
    }

    #[Route('/browse', name: 'app_story_browse', methods: ['GET'])]
    public function browse(
        StoryRepository $storyRepository,
        StoryTemplateManager $storyTemplateManager,
        #[MapQueryParameter]?string $q = null,
        #[MapQueryParameter]string $sort = 'updatedAt',
    ): Response {
        $stories = $storyRepository->findByStoryStatus(StoryStatus::PUBLISHED, $q, $sort);

        return $this->render('story/browse.html.twig', [
            'stories' => $stories,
            'searchTerm' => $q,
            'sortBy' => $sort,
            'templateManager' => $storyTemplateManager,
        ]);
    }

    #[Route('/read/{id}', name: 'app_story_read', methods: ['GET'])]
    #[IsGranted(StoryVoter::READ, subject: 'story')]
    public function read(
        Story $story,
        StoryTemplateManager $templateManager,
    ): Response {
        $chapters = $story->getChapters();

        return $this->render('story/read.html.twig', [
            'story' => $story,
            'chapters' => $chapters,
            'template' => $templateManager->getTemplate($story->getTemplate()),
        ]);
    }

    #[Route('/request-publish/{id}', name: 'app_story_request_publish', methods: ['POST'])]
    #[IsGranted(StoryVoter::EDIT, subject: 'story')]
    public function requestPublish(Story $story, EntityManagerInterface $entityManager): Response
    {
        $story->setStoryStatus(StoryStatus::WAITING_VALIDATION);
        $entityManager->flush();

        $this->addFlash('success', 'Your story has been submitted for validation.');

        return $this->redirectToRoute('app_story_show', ['id' => $story->getId()]);
    }

    #[Route('/moderation', name: 'app_story_moderation_index', methods: ['GET'])]
    #[IsGranted('ROLE_MODERATOR')]
    public function moderationIndex(
        StoryRepository $storyRepository,
        StoryTemplateManager $storyTemplateManager,
        #[MapQueryParameter]?string $q = null,
        #[MapQueryParameter]string $sort = 'updatedAt',
    ): Response {
        $stories = $storyRepository->findByStoryStatus(StoryStatus::WAITING_VALIDATION, $q, $sort);
        $form = $this->createFormBuilder()->getForm();

        return $this->render('story/moderation_list.html.twig', [
            'form' => $form,
            'stories' => $stories,
            'searchTerm' => $q,
            'sortBy' => $sort,
            'templateManager' => $storyTemplateManager,
        ]);
    }

    #[Route('/validate-publish/{id}/{decision}', name: 'app_story_validate_publish', methods: ['POST'])]
    #[IsGranted('ROLE_MODERATOR')]
    #[IsGranted(StoryVoter::MODERATE, subject: 'story')]
    public function validatePublish(
        Story   $story,
        string  $decision,
        Request $request,
        EntityManagerInterface $entityManager,
        Security $security,
    ): Response {
        $moderationMessage = new ModerationMessage();
        $form = $this->createForm(ModerationMessageType::class, $moderationMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $moderationMessage->setStory($story);
            $moderationMessage->setSender($security->getUser());
            $moderationMessage->setReceiver($story->getOwner()->getAccount());
            $entityManager->persist($moderationMessage);
        }

        $status = match ($decision) {
            'validated' => StoryStatus::PUBLISHED,
            'rejected' => StoryStatus::DRAFT,
            default => StoryStatus::WAITING_VALIDATION,
        };
        $story->setStoryStatus($status);
        $entityManager->flush();

        if ('validated' === $decision) {
            $this->addFlash('success', sprintf('Story \'%s\' has been successfully published.', $story->getTitle()));
        } elseif ('rejected' === $decision) {
            $this->addFlash('warning', sprintf('Story \'%s\' has been rejected and set back to draft.', $story->getTitle()));
        }

        return $this->redirectToRoute('app_story_moderation_index');
    }
}
