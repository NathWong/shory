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
}
