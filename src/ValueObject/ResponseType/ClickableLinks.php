<?php

namespace App\ValueObject\ResponseType;

use App\Contract\ChapterLinkTypeInterface;
use App\Entity\Chapter;

readonly class ClickableLinks implements ChapterLinkTypeInterface
{
    public function __construct(
        private Chapter $source,
    )
    {
    }

    public function show(): string
    {
        return 'toto';
    }

    public function edit(): string
    {
        return 'toto';
    }

    public static function getName(): string
    {
        return 'Direct links';
    }
}
