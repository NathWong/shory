<?php

namespace App\ValueObject\StoryTemplate;

use App\Contract\StoryTemplateInterface;

final class SylvanTemplate implements StoryTemplateInterface
{
    public function getName(): string
    {
        return 'Sylvan';
    }

    public function getDescription(): string
    {
        return 'An enchanted, organic and soothing forest atmosphere.';
    }

    public function getIdentifier(): string
    {
        return 'sylvan';
    }

    public function getCssClass(): string
    {
        return 'template-sylvan';
    }
}
