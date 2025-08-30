<?php

namespace App\Contract;

interface ChapterLinkTypeInterface
{
    public function show(): string;

    public function edit(): string;
}
