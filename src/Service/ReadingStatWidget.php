<?php

namespace App\Service;

use App\Entity\Story;
use App\Entity\UserProfile;
use App\Repository\ReadingHistoryRepository;

final readonly class ReadingStatWidget
{
    public function __construct(
        private ReadingHistoryRepository $readingHistoryRepository,
    ) {
    }

    public function getReadingProgresses(UserProfile $userProfile, array $stories): array
    {
        $data = [];
        foreach ($stories as $story) {
            if ($datum = $this->getReadingProgress($userProfile, $story)) {
                $data[$story->getId()] = $datum;
            }
        }

        return $data;
    }

    private function getReadingProgress(UserProfile $userProfile, Story $story): ?int
    {
        $chaptersCount = count($story->getChapters());
        if (!$chaptersCount) {
            return null;
        }

        $chapterReads = $this->readingHistoryRepository->countUniqueChaptersRead($userProfile, $story);

        return round($chapterReads / $chaptersCount * 100);
    }
}
