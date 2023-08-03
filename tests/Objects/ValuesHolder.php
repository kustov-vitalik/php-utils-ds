<?php

declare(strict_types=1);

namespace VKPHPUtils\DS\Tests\Objects;

final class ValuesHolder
{
    public function __construct(
        /** @var array<int, mixed> */
        private array $values = [],
    ) {
    }

    /** @return array<int, mixed> */
    public function getValues(): array
    {
        return $this->values;
    }

    public function append(mixed $v): void
    {
        $this->values[] = $v;
    }
}
