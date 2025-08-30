<?php

namespace App\Contract;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('chapter.link_type')]
interface ChapterLinkTypeInterface
{
    public static function getName(): string;

    public function show(): string;

    public function edit(): string;
}
