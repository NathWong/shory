<?php

namespace App\ValueObject\StoryTemplate;

use App\Contract\StoryTemplateInterface;

final class DefaultTemplate implements StoryTemplateInterface
{
    public function getName(): string
    {
        return 'Default';
    }

    public function getDescription(): string
    {
        return 'A simple and clean default theme.';
    }

    public function getIdentifier(): string
    {
        return 'default';
    }

    public function getCssClass(): string
    {
        return 'template-default';
    }
}
