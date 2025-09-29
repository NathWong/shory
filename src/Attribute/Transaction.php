<?php

namespace App\Attribute;

#[\Attribute(\Attribute::TARGET_METHOD)]
class Transaction
{
    public function __construct(
        public string|array $method = 'POST',
        public array|int|true $on = true, // true or status code or list of status codes
    ) {
    }
}
