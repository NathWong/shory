<?php

namespace App\ValueObject\StoryTemplate;

use App\Contract\StoryTemplateInterface;

class DefaultTemplate implements StoryTemplateInterface
{

    public function getName(): string
    {
        return 'light (default)';
    }

    public function getIdentifier(): string
    {
        return 'default';
    }

    public function getDescription(): string
    {
        return 'A light theme by default';
    }

    public function getCssClass(): string
    {
        return 'template-default';
    }
}
