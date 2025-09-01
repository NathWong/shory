<?php

namespace App\ValueObject\StoryTemplate;

use App\Contract\StoryTemplateInterface;

class DarkTemplate implements StoryTemplateInterface
{

    public function getName(): string
    {
        return 'Dark';
    }

    public function getIdentifier(): string
    {
        return 'dark';
    }

    public function getDescription(): string
    {
        return 'A dark template for a dark story';
    }

    public function getCssClass(): string
    {
        return 'template-dark';
    }
}
