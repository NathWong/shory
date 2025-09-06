<?php

namespace App\Controller;

use App\Entity\ChapterLink;
use App\Entity\ReadingHistory;
use App\Entity\User;
use App\Security\Voter\StoryVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/reading')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class ReadingController extends AbstractController
{
    #[Route('/navigate/{id}', name: 'app_reading_navigate', methods: ['GET'])]
    #[IsGranted(StoryVoter::READ, subject: 'chapterLink')]
    public function navigate(ChapterLink $chapterLink, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            // If no user profile don't record history
            return $this->redirectToRoute('app_chapter_read', ['id' => $chapterLink->getTarget()->getId()]);
        }

        $entry = new ReadingHistory();
        $entry->setUserProfile($user->getUserProfile());
        $entry->setStory($chapterLink->getStory());
        $entry->setChapterLink($chapterLink);

        $entityManager->persist($entry);
        $entityManager->flush();

        return $this->redirectToRoute('app_chapter_read', ['id' => $chapterLink->getTarget()->getId()]);
    }
}
