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
        return 'chapterLink/_clickable_link.html.twig';
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
