<?php

namespace App\Doctrine\Type;

use App\ValueObject\ChapterLinkResponse;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;

class ChapterLinkResponseType extends JsonType
{
    public const string NAME = 'chapter_link_response';

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?ChapterLinkResponse
    {
        if (null === $value || '' === $value) {
            return null;
        }

        $data = is_string($value) ? json_decode($value, true) : $value;

        return new ChapterLinkResponse(
            $data['response'] ?? '',
            $data['aliases'] ?? [],
            $data['translations'] ?? []
        );
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        // Use JsonSerialize in the ValueObject
        return parent::convertToDatabaseValue($value, $platform);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
