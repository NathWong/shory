<?php

namespace App\ValueObject\ResponseType;

use App\Contract\ChapterLinkTypeInterface;

readonly class TextParserInput implements ChapterLinkTypeInterface
{
    public static function getName(): string
    {
        return 'Text Parser';
    }

    public function show(): string
    {
        return 'chapterLink/_text_parser_show.html.twig';
    }

    public function edit(): string
    {
        return 'chapterLink/_text_parser_edit.html.twig';
    }

    public function getIdentifier(): string
    {
        return 'text_parser';
    }
}
