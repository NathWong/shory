<?php

namespace App\Security\Voter;

use App\Entity\Story;
use App\Entity\User;
use App\Enum\StoryStatus;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class StoryVoter extends Voter
{
    public const EDIT = 'STORY_EDIT';
    public const VIEW = 'STORY_VIEW';
    public const READ = 'STORY_READ';
    public const MODERATE = 'STORY_MODERATE';

    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::READ, self::MODERATE])
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

        $isOwner = $story->getOwner()->getAccount() === $user;
        $isModerator = $this->security->isGranted('ROLE_MODERATOR');

        return match ($attribute) {
            self::EDIT => $isOwner,

            self::VIEW => $isOwner || ($isModerator && $story->getStoryStatus() === StoryStatus::WAITING_VALIDATION->value) || $story->getStoryStatus() === StoryStatus::PUBLISHED->value,

            self::READ => $isOwner || $story->getStoryStatus() === StoryStatus::PUBLISHED->value,

            self::MODERATE => $isModerator && $story->getStoryStatus() === StoryStatus::WAITING_VALIDATION->value,

            default => false,
        };
    }
}
