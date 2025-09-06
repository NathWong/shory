<?php

namespace App\Controller;

use App\Entity\Chapter;
use App\Entity\Story;
use App\Form\ChapterType;
use App\Security\Voter\ChapterVoter;
use App\Security\Voter\StoryVoter;
use App\Service\ChapterLinkTypeManager;
use App\Service\StoryTemplateManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use function Symfony\Component\String\u;

#[Route('/chapter')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class ChapterController extends AbstractController
{
    #[Route('/new/{story_id}', name: 'app_chapter_new')]
    #[IsGranted(StoryVoter::EDIT, subject: 'story')]
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
            'chapters' => $story->getChapters(),
        ]);
    }

    #[Route('/show/{id}', name: 'app_chapter_show', methods: ['GET'])]
    #[IsGranted(ChapterVoter::VIEW, subject: 'chapter')]
    public function show(
        Chapter $chapter,
        ChapterLinkTypeManager $manager,
        StoryTemplateManager $templateManager,
    ): Response {
        $template = $templateManager->getTemplateClass($chapter->getStory()) ?? 'default';

        return $this->render('chapter/show.html.twig', [
            'chapter' => $chapter,
            'chapters' => $chapter->getStory()->getChapters(),
            'story' => $chapter->getStory(),
            'link_twig' => $manager->getLinkShowTwig($chapter),
            'template_class' => $template,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_chapter_edit')]
    #[IsGranted(ChapterVoter::EDIT, subject: 'chapter')]
    public function edit(
        Chapter                $chapter,
        Request                $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $form = $this->createForm(ChapterType::class, $chapter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_chapter_show', ['id' => $chapter->getId()]);
        }

        return $this->render('chapter/edit.html.twig', [
            'form' => $form,
            'chapter' => $chapter,
            'story' => $chapter->getStory(),
            'chapters' => $chapter->getStory()->getChapters(),
        ]);
    }

    #[Route('/parse/{id}/{route}', name: 'app_chapter_parse_input', methods: ['POST'])]
    #[IsGranted(ChapterVoter::VIEW, subject: 'chapter')]
    public function parseInput(Chapter $chapter, string $route, Request $request): Response
    {
        $userInput = u($request->get('user_input', ''))
            ->lower()
            ->trim()
            ->toString();

        if (empty($userInput)) {
            return $this->redirectToRoute('app_chapter_show', ['id' => $chapter->getId()]);
        }

        foreach ($chapter->getChapterLinks() as $link) {
            if ($link->getResponses()->isValid($userInput)) {
                return $this->redirectToRoute('app_chapter_show', ['id' => $link->getTarget()->getId()]);
            }
        }

        $this->addFlash('warning', sprintf('The command \'%s\' did not lead anywhere.', $userInput));
        $target = match ($route) {
            'show' => 'app_chapter_show',
            default => 'app_chapter_read',
        };

        return $this->redirectToRoute($target, ['id' => $chapter->getId()]);
    }

    #[Route('/read/{id}', name: 'app_chapter_read', methods: ['GET'])]
    #[IsGranted(StoryVoter::READ, subject: 'chapter')]
    public function read(
        Chapter $chapter,
        ChapterLinkTypeManager $manager,
        StoryTemplateManager $templateManager,
    ): Response {
        $template = $templateManager->getTemplateClass($chapter->getStory()) ?? 'default';

        return $this->render('chapter/read.html.twig', [
            'chapter' => $chapter,
            'story' => $chapter->getStory(),
            'link_twig' => $manager->getLinkShowTwig($chapter),
            'template_class' => $template,
        ]);
    }
}
