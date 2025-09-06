<?php

namespace App\ValueObject\StoryTemplate;

use App\Contract\StoryTemplateInterface;

final class DarkTemplate implements StoryTemplateInterface
{
    public function getName(): string
    {
        return 'Dark';
    }

    public function getDescription(): string
    {
        return 'A dark theme for a dark story.';
    }

    public function getIdentifier(): string
    {
        return 'dark';
    }

    public function getCssClass(): string
    {
        return 'template-dark';
    }
}
