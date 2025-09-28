<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum ContributorRoleEnum: string implements TranslatableInterface
{
    // The reviewer can read unpublish story and add notes
    case REVIEWER = 'reviewer';

    // The writer can edit chapters and add translations
    case WRITER = 'writer';

    // The Editor can add chapters and modify links
    case EDITOR = 'editor';

    // The co-author can manage contributors, delete chapters and see statistics
    case CO_AUTHOR = 'co_author';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans($this->value, locale: $locale);
    }
}
