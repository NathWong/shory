<?php

namespace App\Security\Voter;

use App\Entity\Chapter;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ChapterVoter extends Voter
{
    public const EDIT = 'CHAPTER_EDIT';
    public const VIEW = 'CHAPTER_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof Chapter;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Chapter $chapter */
        $chapter = $subject;

        // A user can view or edit a chapter if they own the story it belongs to.
        return match ($attribute) {
            self::EDIT, self::VIEW => $chapter->getStory()->getStoryGroup()->getOwner()->getAccount() === $user,
            default => false,
        };
    }
}
