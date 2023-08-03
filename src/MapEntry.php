<?php

declare(strict_types=1);

namespace VKPHPUtils\DS;

/**
 * @template TKey
 * @template TValue
 */
final class MapEntry
{

    /**
     * @param TKey $key
     * @param TValue $value
     */
    public function __construct(
        public readonly mixed $key,
        public mixed $value
    ) {
    }
}
