<?php

declare(strict_types=1);

namespace VKPHPUtils\DS;

use Iterator;

/**
 * A collection that contains no duplicate elements. At most one null element.
 * As implied by its name, this interface models the mathematical set abstraction.
 *
 * @template T
 * @template-extends Collection<T>
 */
class Set extends Collection
{
    /** @var array<int, T> */
    private array $data = [];
    private int $size = 0;

    /**
     * @param T ...$elements
     */
    public function __construct(mixed ...$elements)
    {
        foreach ($elements as $element) {
            $this->add($element);
        }
    }

    /**
     * @inheritDoc
     */
    public function add(mixed $e): bool
    {
        $index = in_array($e, $this->data, false);

        if ($index === false) {
            $this->data[] = $e;
            $this->size++;
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function clear(): void
    {
        $this->data = [];
        $this->size = 0;
    }

    /**
     * @inheritDoc
     */
    public function remove(mixed $e): void
    {
        $index = array_search($e, $this->data, true);
        if ($index !== false) {
            unset($this->data[$index]);
            $this->size--;
        }
    }

    /**
     * @inheritDoc
     */
    public function size(): int
    {
        return $this->size;
    }

    /**
     * @inheritDoc
     */
    public function iterator(): Iterator
    {
        return new \ArrayIterator($this->toArray());
    }

    public function toArray(): array
    {
        return array_values($this->data);
    }

}
