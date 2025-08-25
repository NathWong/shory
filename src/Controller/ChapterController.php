<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\Story;
use App\Form\ChapterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/chapter')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class ChapterController extends AbstractController
{
    #[Route('/new/{story_id}', name: 'app_chapter_new')]
    public function create(
        #[MapEntity(mapping: ['story_id' => 'id'])]
        Story                  $story,
        Request                $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $chapter = new Chapter();
        $form = $this->createForm(ChapterType::class, $chapter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chapter->setStory($story);

            $entityManager->persist($chapter);
            $entityManager->flush();

            return $this->redirectToRoute('app_story_show', ['id' => $story->getId()]);
        }

        return $this->render('chapter/new.html.twig', [
            'form' => $form,
            'story' => $story,
        ]);
    }

    #[Route('/show/{id}', name: 'app_chapter_show', methods: ['GET'])]
    public function show(Chapter $chapter): Response
    {
        return $this->render('chapter/show.stream.html.twig', ['chapter' => $chapter]);
    }
}
