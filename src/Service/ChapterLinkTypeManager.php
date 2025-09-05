<?php

namespace App\Service;

use App\Contract\ChapterLinkTypeInterface;
use App\Entity\Chapter;
use RuntimeException;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class ChapterLinkTypeManager
{
    /**
     * @param iterable<ChapterLinkTypeInterface> $linkTypes
     */
    public function __construct(
        #[AutowireIterator('chapter.link_type')]
        private iterable $linkTypes,
    ) {
    }

    public function getChoices(): array
    {
        $choices = [];
        foreach ($this->linkTypes as $linkType) {
            $choices[$linkType::getName()] = $linkType->getIdentifier();
        }

        return $choices;
    }

    public function getLinkShowTwig(Chapter $chapter): ?string
    {
        if (!$identifier = $chapter->getLinkType()){
            return null;
        }

        foreach ($this->linkTypes as $linkType) {
            if ($linkType->getIdentifier() === $identifier) {
                return $linkType->show();
            }
        }

        throw new RuntimeException(sprintf('Unknown chapter link type "%s".', $identifier));
    }

    public function getLinkEditTwig(Chapter $chapter): ?string
    {
        if (!$identifier = $chapter->getLinkType()){
            return null;
        }

        foreach ($this->linkTypes as $linkType) {
            if ($linkType->getIdentifier() === $identifier) {
                return $linkType->edit();
            }
        }

        throw new RuntimeException(sprintf('Unknown chapter link type "%s".', $identifier));
    }
}
