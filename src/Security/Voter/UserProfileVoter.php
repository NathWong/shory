<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\UserProfile;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserProfileVoter extends Voter
{
    public const EDIT = 'PROFILE_EDIT';
    public const DELETE = 'PROFILE_DELETE';

    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof UserProfile;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        /** @var UserProfile $profile */
        $profile = $subject;

        return match ($attribute) {
            self::EDIT, self::DELETE => $profile->getAccount() === $user,
            default => false,
        };
    }
}
