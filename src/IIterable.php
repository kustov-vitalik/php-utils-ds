<?php

declare(strict_types=1);

namespace VKPHPUtils\DS;

use Iterator;
use IteratorAggregate;

/**
 * @template T
 * @template-implements IteratorAggregate<int, T>
 */
abstract class IIterable implements IteratorAggregate
{
    /**
     * Performs the given action for each element of the Iterable
     *  until all elements have been processed or the action throws an exception.
     *
     * @param \Closure(T): void $action
     * @return void
     */
    public function forEach(\Closure $action): void
    {
        foreach ($this as $element) {
            $action($element);
        }
    }

    /**
     * Returns an iterator over elements of type T.
     *
     * @return Iterator<int, T>
     */
    abstract public function iterator(): Iterator;

    /**
     * @return Iterator<int, T>
     */
    final public function getIterator(): Iterator
    {
        return $this->iterator();
    }
}
