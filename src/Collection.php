<?php

declare(strict_types=1);

namespace VKPHPUtils\DS;

use Closure;

/**
 * @template T
 * @template-extends IIterable<T>
 */
abstract class Collection extends IIterable
{
    /**
     * Adds all of the elements in the specified collection to this collection.
     *
     * @param Collection<T> $collection
     * @return bool
     */
    public function addAll(Collection $collection): bool
    {
        $modified = false;
        foreach ($collection as $element) {
            $this->add($element);
            $modified = true;
        }
        return $modified;
    }

    /**
     * Ensures that this collection contains the specified element.
     * @param T $e
     * @return bool
     */
    abstract public function add(mixed $e): bool;

    /**
     * Removes all of the elements from this collection.
     * @return void
     */
    abstract public function clear(): void;

    /**
     * Returns true if this collection contains all of the elements in the specified collection.
     *
     * @template P
     * @param Collection<P> $collection
     * @return bool
     */
    public function containsAll(Collection $collection): bool
    {
        foreach ($collection as $element) {
            if (!$this->contains($element)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Returns true if this collection contains the specified element.
     * @param mixed $e
     * @return bool
     */
    public function contains(mixed $e): bool
    {
        foreach ($this as $element) {
            if ($element == $e) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns true if this collection contains no elements.
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->size() === 0;
    }

    /**
     * Returns the number of elements in this collection.
     *
     * @return int
     */
    abstract public function size(): int;

    /**
     * Removes all of this collection's elements
     *  that are also contained in the specified collection.
     * @param Collection<mixed> $collection
     * @return void
     */
    public function removeAll(Collection $collection): void
    {
        foreach ($this as $element) {
            if ($collection->contains($element)) {
                $this->remove($element);
            }
        }
    }

    /**
     * Removes a single instance of the specified element from this collection, if it is present.
     * @param mixed $e
     * @return void
     */
    abstract public function remove(mixed $e): void;

    /**
     * Removes all of the elements of this collection that satisfy the given predicate.
     * @param Closure(T):bool $filter
     * @return bool
     */
    public function removeIf(Closure $filter): bool
    {
        $modified = false;
        foreach ($this as $element) {
            if ($filter($element)) {
                $this->remove($element);
                $modified = true;
            }
        }
        return $modified;
    }

    /**
     * Retains only the elements in this collection that are contained in the specified collection.
     * @param Collection<mixed> $collection
     * @return bool
     */
    public function retainAll(Collection $collection): bool
    {
        $modified = false;
        foreach ($this as $element) {
            if (!$collection->contains($element)) {
                $this->remove($element);
                $modified = true;
            }
        }
        return $modified;
    }

    /**
     * @return list<T>
     */
    abstract public function toArray(): array;
}
