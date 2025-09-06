<?php

namespace App\ValueObject\StoryTemplate;

use App\Contract\StoryTemplateInterface;

final class ParchmentTemplate implements StoryTemplateInterface
{
    public function getName(): string
    {
        return 'Parchment';
    }

    public function getDescription(): string
    {
        return 'A medieval manuscript or an old quest journal.';
    }

    public function getIdentifier(): string
    {
        return 'parchment';
    }

    public function getCssClass(): string
    {
        return 'template-parchment';
    }
}
