<?php

namespace App\Controller;

use App\Entity\Story;
use App\Enum\StoryStatus;
use App\Form\NewStoryType;
use App\Repository\StoryRepository;
use App\Trait\ProfiledUserTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\Turbo\TurboBundle;

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

        return $this->render('story/new.stream.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/index', name: 'app_story_index', methods: ['GET'])]
    public function index(StoryRepository $storyRepository, Request $request): Response
    {
        $stories = $storyRepository->findBy(['owner' => $this->getUserProfile()->getId()], ['updatedAt' => 'DESC']);
        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

        return $this->render('story/index.stream.html.twig', ['stories' => $stories]);
    }

    #[Route('/show/{id}', name: 'app_story_show', methods: ['GET'])]
    public function show(Request $request, Story $story): Response
    {
        $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
        $chapters = $story->getChapters();

        return $this->render('story/show.stream.html.twig', ['story' => $story, 'chapters' => $chapters]);
    }
}
