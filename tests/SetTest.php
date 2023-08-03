<?php

declare(strict_types=1);

namespace VKPHPUtils\DS\Tests;

use PHPUnit\Framework\Attributes\Test;
use VKPHPUtils\DS\Set;
use PHPUnit\Framework\TestCase;

class SetTest extends TestCase
{
    #[Test]
    public function emptySet(): void
    {
        $set = new Set();
        $this->assertTrue($set->isEmpty());
        $this->assertSame(0, $set->size());
        $this->assertSame([], $set->toArray());
    }

    #[Test]
    public function toArray(): void
    {
        $set = new Set('item1', 'item2', 'item3');
        $this->assertSame(['item1', 'item2', 'item3'], $set->toArray());
    }

    #[Test]
    public function add(): void
    {
        $set = new Set();
        $this->assertTrue($set->add('item1'));
        $this->assertTrue($set->add('item2'));
        $this->assertFalse($set->add('item1')); // Duplicates are not allowed in a Set

        $this->assertSame(2, $set->size());
        $this->assertSame(['item1', 'item2'], $set->toArray());
    }

    #[Test]
    public function remove(): void
    {
        $set = new Set('item1', 'item2', 'item3');
        $set->remove('item2');

        $this->assertSame(2, $set->size());
        $this->assertSame(['item1', 'item3'], $set->toArray());

        // Removing a non-existent item should not cause any issue
        $set->remove('non_existent_item');
        $this->assertSame(2, $set->size());
    }

    #[Test]
    public function clear(): void
    {
        $set = new Set('item1', 'item2');
        $set->clear();

        $this->assertTrue($set->isEmpty());
        $this->assertSame(0, $set->size());
        $this->assertSame([], $set->toArray());
    }

    #[Test]
    public function iterator(): void
    {
        $set = new Set('item1', 'item2', 'item3');

        $items = [];
        foreach ($set as $item) {
            $items[] = $item;
        }

        $this->assertSame(['item1', 'item2', 'item3'], $items);
    }
}
