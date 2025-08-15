<?php

namespace App\Contract;

use App\Entity\UserProfile;

interface ProfiledUserInterface
{
    public function getUserProfile(): ?UserProfile;
}
