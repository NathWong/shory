<?php

namespace App\Contract;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.story_template')]
interface StoryTemplateInterface
{
    public function getName(): string;

    public function getIdentifier(): string;

    public function getDescription(): string;

    public function getCssClass(): string;
}
