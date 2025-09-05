<?php

namespace App\ValueObject\StoryTemplate;

use App\Contract\StoryTemplateInterface;

final class CyberpunkTemplate implements StoryTemplateInterface
{
    public function getName(): string
    {
        return 'Cyberpunk';
    }

    public function getDescription(): string
    {
        return 'A futuristic, dark, high-tech terminal interface.';
    }

    public function getIdentifier(): string
    {
        return 'cyberpunk';
    }

    public function getCssClass(): string
    {
        return 'template-cyberpunk';
    }
}
