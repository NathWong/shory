<?php

namespace App\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
class DoctrineHistory
{
    public function __construct(
        public int $historyLength = 0, // max history kipped, 0 for no limit
    ) {
    }
}
