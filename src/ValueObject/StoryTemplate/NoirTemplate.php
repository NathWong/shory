<?php

namespace App\ValueObject\StoryTemplate;

use App\Contract\StoryTemplateInterface;

final class NoirTemplate implements StoryTemplateInterface
{
    public function getName(): string
    {
        return 'Noir';
    }

    public function getDescription(): string
    {
        return 'The atmosphere of a 1940s film noir.';
    }

    public function getIdentifier(): string
    {
        return 'noir';
    }

    public function getCssClass(): string
    {
        return 'template-noir';
    }
}
