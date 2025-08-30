<?php

namespace App\Service;

use App\Contract\ChapterLinkTypeInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

class ChapterLinkTypeManager
{
    public function __construct(
        /** @var iterable|ChapterLinkTypeInterface[] $linkTypes */
        #[AutowireIterator('chapter.link_type')]
        private iterable $linkTypes,
    ) {
    }

    public function getChapterLinkTypes(): array
    {
        $data = [];
        foreach ($this->linkTypes as $linkType) {
            $data[$linkType::class] = $linkType::getName();
        }

        return $data;
    }
}
