<?php

namespace App\Security;

use App\Contract\ProfiledUserInterface;
use App\Entity\Chapter;
use App\Entity\Story;

class StoryEditorManager
{
    Public function canEditStory(ProfiledUserInterface $user, Story $story): bool
    {
        return $this->isOwner($user, $story);
    }

    public function canEditChapter(ProfiledUserInterface $user, Chapter $chapter): bool
    {
        return $this->canEditStory($user, $chapter->getStory()) || $this->isContributor($user, $chapter->getStory());
    }

    public function canAddChapter(ProfiledUserInterface $user, Story $story): bool
    {
        return $this->isOwner($user, $story);
    }

    public function canDeleteStory(ProfiledUserInterface $user, Story $story): bool
    {
        return $this->isOwner($user, $story);
    }

    public function canDeleteChapter(ProfiledUserInterface $user, Chapter $chapter): bool
    {
        return $this->isOwner($user, $chapter->getStory());
    }

    private function isOwner(ProfiledUserInterface $user, Story $story): bool
    {
        return $user->getUserProfile() === $story->getOwner();
    }

    private function isContributor(ProfiledUserInterface $user, Story $story): bool
    {
        return $story->getContributors()->contains($user->getUserProfile());
    }
}
