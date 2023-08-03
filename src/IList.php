<?php

declare(strict_types=1);

namespace VKPHPUtils\DS;

/**
 * @template T
 * @template-extends Collection<T>
 */
abstract class IList extends Collection
{
    /**
     * Appends the specified element to the end of this list (optional operation).
     *
     * @param T $e
     * @return bool
     */
    abstract public function add(mixed $e): bool;

    /**
     * Inserts the specified element at the specified position in this list (optional operation).
     *
     * @param int $index
     * @param T $e
     * @return void
     */
    abstract public function addByIndex(int $index, mixed $e): void;

    /**
     * Inserts all of the elements in the specified collection into this list
     *  at the specified position (optional operation).
     *
     * @param int $index
     * @param Collection<T> $collection
     * @return bool
     */
    abstract public function addAllByIndex(int $index, Collection $collection): bool;

    /**
     * Returns the element at the specified position in this list.
     *
     * @param int $index
     * @return T
     */
    abstract public function get(int $index): mixed;

    /**
     * Returns the index of the last occurrence of the specified element in this list, or -1
     *  if this list does not contain the element.
     *
     * @param mixed $e
     * @return int
     */
    abstract public function lastIndexOf(mixed $e): int;

    /**
     * Removes the first occurrence of the specified element from this list, if it is present (optional operation).
     *
     * @param T $e element to be removed from this list, if present
     * @return void
     */
    public function remove(mixed $e): void
    {
        $indexOf = $this->indexOf($e);
        if ($indexOf !== -1) {
            $this->removeByIndex($indexOf);
        }
    }

    /**
     * Returns the index of the first occurrence of the specified element in this list, or -1
     *  if this list does not contain the element.
     *
     * @param mixed $e
     * @return int
     */
    abstract public function indexOf(mixed $e): int;

    /**
     * Removes the element at the specified position in this list (optional operation).
     * Shifts any subsequent elements to the left (subtracts one from their indices).
     * Returns the element that was removed from the list.
     *
     * @param int $index the index of the element to be removed
     * @return T the element previously at the specified position
     */
    abstract public function removeByIndex(int $index): mixed;

    /**
     * Replaces each element of this list with the result of applying the operator to that element.
     *
     * @param \Closure(T):T $operator
     * @return void
     */
    public function replaceAll(\Closure $operator): void
    {
        foreach ($this as $index => $element) {
            $this->set($index, $operator($element));
        }
    }

    /**
     * Replaces the element at the specified position in this list with the specified element (optional operation).
     *
     * @param int $index
     * @param T $e
     * @return T
     */
    abstract public function set(int $index, mixed $e): mixed;

    // todo sort
    //public function sort();

    /**
     * Returns a view of the portion of this list between the specified fromIndex, inclusive, and toIndex, exclusive.
     *
     * @param int $fromIndex
     * @param int $toIndex
     * @return IList<T>
     */
    abstract public function subList(int $fromIndex, int $toIndex): IList;

}
