<?php

namespace App\ValueObject\StoryTemplate;

use App\Contract\StoryTemplateInterface;

final class SteampunkTemplate implements StoryTemplateInterface
{
    public function getName(): string
    {
        return 'Steampunk';
    }

    public function getDescription(): string
    {
        return 'A mix of Victorian elegance and steam-powered mechanics.';
    }

    public function getIdentifier(): string
    {
        return 'steampunk';
    }

    public function getCssClass(): string
    {
        return 'template-steampunk';
    }
}
