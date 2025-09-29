<?php

namespace App\Controller;

use App\Attribute\Transaction;
use App\Entity\ModerationMessage;
use App\Entity\Story;
use App\Entity\StoryGroup;
use App\Enum\StoryStatus;
use App\Form\ModerationMessageType;
use App\Form\NewStoryFormType;
use App\Repository\ModerationMessageRepository;
use App\Repository\ReadingHistoryRepository;
use App\Repository\StoryGroupRepository;
use App\Repository\StoryRepository;
use App\Security\Voter\StoryVoter;
use App\Service\ReadingStatWidget;
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
        $form = $this->createForm(NewStoryFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var StoryGroup $storyGroup */
            $storyGroup = $form->get('storyGroup')->getData();
            /** @var Story $version */
            $version = $form->get('version')->getData();

            // Set owner on the group
            $storyGroup->setOwner($this->getUserProfile());

            // Set version, status, and associate the story with its group
            $version->setStoryStatus(StoryStatus::DRAFT);
            $version->setStoryGroup($storyGroup);

            $entityManager->persist($storyGroup);
            $entityManager->persist($version);
            $entityManager->flush();

            return $this->redirectToRoute('app_story_show', ['id' => $version->getId()]);
        }

        return $this->render('story/new.html.twig', [
            'form' => $form,
            'edit' => false,
        ]);
    }

    #[Route(
        '/index',
        name: 'app_story_index',
        methods: ['GET'],
    )]
    public function index(
        StoryGroupRepository $storyGroupRepository,
        StoryTemplateManager $storyTemplateManager,
        #[MapQueryParameter] ?string $q = null,
        #[MapQueryParameter] string $sort = 'title',
    ): Response {
        $storyGroups = $storyGroupRepository->findByOwner($this->getUserProfile(), $q, $sort);

        return $this->render('story/index.html.twig', [
            'storyGroups' => $storyGroups,
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
        Security $security,
    ): Response {
        $chapters = $story->getChapters();
        $form = $this->createFormBuilder()->getForm();

        $moderationMessages = [];
        // Only show moderation messages to the owner, or to a moderator/admin
        if ($this->isGranted('ROLE_MODERATOR') || $story->getStoryGroup()->getOwner()->getAccount() === $security->getUser()) {
            $moderationMessages = $moderationMessageRepository->findBy(['story' => $story], ['createdAt' => 'DESC']);
        }

        return $this->render('story/show.html.twig', [
            'form' => $form,
            'story' => $story,
            'chapters' => $chapters,
            'template' => $templateManager->getTemplate($story->getStoryGroup()->getTemplate()),
            'moderationMessages' => $moderationMessages,
        ]);
    }

    #[IsGranted(StoryVoter::EDIT, subject: 'story')]
    #[Route('/edit/{id}', name: 'app_story_edit', methods: ['GET', 'POST'])]
    #[Transaction]
    public function edit(
        Story $story,
        Request $request,
    ): Response {
        $data = [
            'storyGroup' => $story->getStoryGroup(),
            'version' => $story,
        ];

        $form = $this->createForm(NewStoryFormType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

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
        ReadingStatWidget $statWidget,
        #[MapQueryParameter] ?string $q = null,
        #[MapQueryParameter] string $sort = 'updatedAt',
    ): Response {
        $stories = $storyRepository->findByStoryStatus(StoryStatus::PUBLISHED, $q, $sort);

        $readingProgress = $statWidget->getReadingProgresses($this->getUserProfile(), $stories);

        return $this->render('story/browse.html.twig', [
            'stories' => $stories,
            'searchTerm' => $q,
            'sortBy' => $sort,
            'templateManager' => $storyTemplateManager,
            'readingProgress' => $readingProgress,
        ]);
    }

    #[Route('/read/{id}', name: 'app_story_read', methods: ['GET'])]
    #[IsGranted(StoryVoter::READ, subject: 'story')]
    public function read(
        Story $story,
        StoryTemplateManager $templateManager,
        ReadingHistoryRepository $readingHistoryRepository,
    ): Response {
        $chapters = $story->getChapters();

        $lastRead = $readingHistoryRepository->findOneBy(
            [
                'userProfile' => $this->getUserProfile(),
                'story' => $story,
            ],
            ['createdAt' => 'DESC'],
        );

        return $this->render('story/read.html.twig', [
            'story' => $story,
            'chapters' => $chapters,
            'template' => $templateManager->getTemplate($story->getStoryGroup()->getTemplate()),
            'lastRead' => $lastRead,
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
        #[MapQueryParameter] ?string $q = null,
        #[MapQueryParameter] string $sort = 'updatedAt',
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
        Story $story,
        string $decision,
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
            $moderationMessage->setReceiver($story->getStoryGroup()->getOwner()->getAccount());
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
