<?php

namespace App\Enum;

enum StoryGenreEnum: string
{
    case Undefined = 'undefined';
    case ScienceFiction = 'Science Fiction';
    case Fantasy = 'Fantasy';
    case Thriller = 'Thriller';
    case Romance = 'Romance';
    case Horror = 'Horror';
    case Mainstream = 'Mainstream';
    case Mystery = 'Mystery';
    case Adventure = 'Adventure';
    case HistoricalFiction = 'Historical Fiction';
    case Comedy = 'Comedy';
    case Drama = 'Drama';

    public function getColorClass(): string
    {
        return match ($this) {
            self::Horror, self::Thriller => 'text-bg-danger',
            self::Fantasy, self::Adventure => 'text-bg-success',
            self::ScienceFiction, self::Mystery => 'text-bg-primary',
            self::Romance, self::Comedy => 'text-bg-warning',
            self::HistoricalFiction, self::Drama => 'text-bg-info',
            default => 'text-bg-secondary',
        };
    }
}
