<?php

namespace App\Security\Voter;

use App\Contract\StoriableInterface;
use App\Entity\Story;
use App\Entity\User;
use App\Enum\StoryStatus;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

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
            && ($subject instanceof Story || $subject instanceof StoriableInterface);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Story $story */
        $story = $subject instanceof Story ? $subject : $subject->getStory();

        $isOwner = $story->getOwner()->getAccount() === $user;
        $isModerator = $this->security->isGranted('ROLE_MODERATOR');

        return match ($attribute) {
            self::EDIT => $isOwner,

            self::VIEW => $isOwner || ($isModerator && $story->getStoryStatus() === StoryStatus::WAITING_VALIDATION) || $story->getStoryStatus() === StoryStatus::PUBLISHED,

            self::READ => $isOwner || $story->getStoryStatus() === StoryStatus::PUBLISHED,

            self::MODERATE => $isModerator && $story->getStoryStatus() === StoryStatus::WAITING_VALIDATION,

            default => false,
        };
    }
}
