<?php

namespace App\ValueObject\ResponseType;

use App\Contract\ChapterLinkTypeInterface;

readonly class ClickableLinks implements ChapterLinkTypeInterface
{
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

    public function getIdentifier(): string
    {
        return 'clickable_links';
    }
}
