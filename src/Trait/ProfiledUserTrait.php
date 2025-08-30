<?php

namespace App\Trait;

use App\Contract\ProfiledUserInterface;
use App\Entity\UserProfile;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

trait ProfiledUserTrait
{
    private function getProfiledUser(): ProfiledUserInterface
    {
        $user = $this->getUser();

        if (!($user instanceof ProfiledUserInterface)) {
            throw new UnsupportedUserException('User should implement '.ProfiledUserInterface::class);
        }

        return $user;
    }

    private function getUserProfile(): ?UserProfile
    {
        return $this->getProfiledUser()->getUserProfile();
    }
}
