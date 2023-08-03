<?php

declare(strict_types=1);

namespace VKPHPUtils\DS;

use ArrayIterator;
use Iterator;
use OutOfRangeException;

/**
 * @template T
 * @template-extends IList<T>
 */
class ArrayList extends IList
{
    /** @var list<T> */
    private array $data = [];

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
        $this->data[] = $e;
        return true;
    }

    /**
     * @inheritDoc
     */
    public function iterator(): Iterator
    {
        return new ArrayIterator($this->toArray());
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function size(): int
    {
        return count($this->data);
    }

    /**
     * @inheritDoc
     */
    public function addAllByIndex(int $index, Collection $collection): bool
    {
        $modified = false;
        foreach ($collection as $element) {
            $this->addByIndex($index++, $element);
            $modified = true;
        }
        return $modified;
    }

    /**
     * @inheritDoc
     */
    public function addByIndex(int $index, mixed $e): void
    {
        if ($index >= 0 && $index <= count($this->data)) {
            array_splice($this->data, $index, 0, [$e]);
        } else {
            throw new OutOfRangeException();
        }
    }

    /**
     * @inheritDoc
     */
    public function lastIndexOf(mixed $e): int
    {
        for (end($this->data); (($currentKey = key($this->data)) !== null); prev($this->data)) {
            if ($e == current($this->data)) {
                return $currentKey;
            }
        }

        return -1;
    }

    /**
     * @inheritDoc
     */
    public function removeByIndex(int $index): mixed
    {
        if (isset($this->data[$index])) {
            $element = $this->data[$index];
            array_splice($this->data, $index, 1);
            return $element;
        }

        throw new OutOfRangeException();
    }

    /**
     * @inheritDoc
     */
    public function indexOf(mixed $e): int
    {
        $idx = array_search($e, $this->data);
        if ($idx !== false) {
            return $idx;
        }

        return -1;
    }

    /**
     * @inheritDoc
     */
    public function get(int $index): mixed
    {
        return $this->data[$index] ?? throw new OutOfRangeException();
    }

    /**
     * @inheritDoc
     */
    public function set(int $index, mixed $e): mixed
    {
        if (isset($this->data[$index])) {
            $oldElement = $this->data[$index];
            $this->data[$index] = $e;
            return $oldElement;
        }

        throw new OutOfRangeException();
    }

    /**
     * @inheritDoc
     */
    public function subList(int $fromIndex, int $toIndex): IList
    {
        if ($fromIndex < 0 || $fromIndex > $toIndex || $toIndex > count($this->data)) {
            throw new OutOfRangeException();
        }

        $subList = new ArrayList();
        for ($i = $fromIndex; $i < $toIndex; $i++) {
            $subList->add($this->data[$i]);
        }

        return $subList;
    }

    /**
     * @inheritDoc
     */
    public function clear(): void
    {
        $this->data = [];
    }

}
