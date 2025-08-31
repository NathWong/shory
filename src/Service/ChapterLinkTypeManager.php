<?php

namespace App\Service;

use App\Contract\ChapterLinkTypeInterface;
use App\Entity\Chapter;
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

    public function getLinkShowTwig(Chapter $chapter): string
    {
        $typeClass = $chapter->getLinkType();
        $chapterType = new $typeClass($chapter);
        if (!$chapterType instanceof ChapterLinkTypeInterface) {
            throw new \RuntimeException(sprintf(
                'Chapter links type "%s" does not implement "%s".',
                $typeClass,
                ChapterLinkTypeInterface::class,
            ));
        }

        return $chapterType->show();
    }
}
