<?php

namespace App\Contract;

use App\Entity\Story;

interface StoriableInterface
{
    public function getStory(): ?Story;
}
