<?php

namespace App\Form\DataTransformer;

use App\ValueObject\ChapterLinkResponse;
use Symfony\Component\Form\DataTransformerInterface;

class ResponseToStringTransformer implements DataTransformerInterface
{
    public function transform(mixed $value): string
    {
        if (null === $value) {
            return '';
        }

        if (!$value instanceof ChapterLinkResponse) {
            throw new \LogicException('The ChapterLinkResponseToStringTransformer can only be used with ChapterLinkResponse objects.');
        }

        return $value->getResponse();
    }

    public function reverseTransform(mixed $value): mixed
    {
        return new ChapterLinkResponse($value ?? '');
    }
}
