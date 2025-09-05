<?php

namespace App\ValueObject\StoryTemplate;

use App\Contract\StoryTemplateInterface;

final class CrimsonTemplate implements StoryTemplateInterface
{
    public function getName(): string
    {
        return 'Crimson';
    }

    public function getDescription(): string
    {
        return 'A gothic, dramatic and intense style.';
    }

    public function getIdentifier(): string
    {
        return 'crimson';
    }

    public function getCssClass(): string
    {
        return 'template-crimson';
    }
}
