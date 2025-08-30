<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum StoryStatus: string implements TranslatableInterface
{
    case DRAFT = 'draft';
    case WAITING_VALIDATION = 'waiting validation';
    case PUBLISHED = 'published';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans($this->value, locale: $locale);
    }
}
