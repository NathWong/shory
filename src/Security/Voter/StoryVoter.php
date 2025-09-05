<?php

namespace App\Security\Voter;

use App\Entity\Story;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class StoryVoter extends Voter
{
    public const EDIT = 'STORY_EDIT';
    public const VIEW = 'STORY_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof Story;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Story $story */
        $story = $subject;

        return match ($attribute) {
            self::EDIT, self::VIEW => $story->getOwner()->getAccount() === $user,
            default => false,
        };
    }
}
