<?php

declare(strict_types=1);

namespace VKPHPUtils\DS\Tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use VKPHPUtils\DS\ArrayList;

class ArrayListTest extends TestCase
{
    #[Test]
    public function addAndGet(): void
    {
        $list = new ArrayList();
        $list->add(10);
        $list->add(20);
        $list->add(30);

        $this->assertEquals(10, $list->get(0));
        $this->assertEquals(20, $list->get(1));
        $this->assertEquals(30, $list->get(2));
    }

    #[Test]
    public function addAllByIndex(): void
    {
        $list1 = new ArrayList();
        $list1->add(10);
        $list1->add(20);

        $list2 = new ArrayList();
        $list2->add(30);
        $list2->add(40);

        $list1->addAllByIndex(1, $list2); // Inserts elements of $list2 after index 1 in $list1

        $this->assertEquals(10, $list1->get(0));
        $this->assertEquals(30, $list1->get(1));
        $this->assertEquals(40, $list1->get(2));
        $this->assertEquals(20, $list1->get(3));
    }

    #[Test]
    public function remove(): void
    {
        $list = new ArrayList();
        $list->add(10);
        $list->add(20);
        $list->add(30);

        $list->remove(20); // Removes the first occurrence of 20

        $this->assertEquals(10, $list->get(0));
        $this->assertEquals(30, $list->get(1));
    }

    #[Test]
    public function contains(): void
    {
        $list = new ArrayList();
        $list->add(10);
        $list->add(20);
        $list->add(30);

        $this->assertTrue($list->contains(20));
        $this->assertFalse($list->contains(40));
    }

    #[Test]
    public function checkSize(): void
    {
        $list = new ArrayList();
        $this->assertEquals(0, $list->size());

        $list->add(10);
        $list->add(20);
        $list->add(30);
        $this->assertEquals(3, $list->size());
    }

    #[Test]
    public function checkEmpty(): void
    {
        $list = new ArrayList();
        $this->assertTrue($list->isEmpty());

        $list->add(10);
        $this->assertFalse($list->isEmpty());
    }

    #[Test]
    public function iterator(): void
    {
        /** @var ArrayList<int> $list */
        $list = new ArrayList();
        $list->add(10);
        $list->add(20);
        $list->add(30);

        $expectedArray = [10, 20, 30];
        $resultArray = [];
        foreach ($list as $element) {
            $resultArray[] = $element;
        }

        $this->assertEquals($expectedArray, $resultArray);
    }

    #[Test]
    public function toArray(): void
    {
        $list = new ArrayList();
        $list->add(10);
        $list->add(20);
        $list->add(30);

        $expectedArray = [10, 20, 30];
        $resultArray = $list->toArray();

        $this->assertEquals($expectedArray, $resultArray);
    }

    #[Test]
    public function removeIf(): void
    {
        /** @var ArrayList<int> $list */
        $list = new ArrayList();
        $list->add(10);
        $list->add(20);
        $list->add(30);

        // Remove all even numbers
        $list->removeIf(function (int $element): bool {
            return $element / 10 % 2 === 0;
        });

        $this->assertEquals(10, $list->get(0));
    }

    #[Test]
    public function retainAll(): void
    {
        $list1 = new ArrayList();
        $list1->add(10);
        $list1->add(20);
        $list1->add(30);

        $list2 = new ArrayList();
        $list2->add(20);
        $list2->add(40);

        $list1->retainAll($list2); // Keeps only elements that are present in $list2

        $this->assertEquals(20, $list1->get(0));
        $this->assertCount(1, $list1);
    }

    #[Test]
    public function clear(): void
    {
        $list = new ArrayList();
        $list->add(10);
        $list->add(20);
        $list->add(30);

        $this->assertFalse($list->isEmpty());

        $list->clear();

        $this->assertTrue($list->isEmpty());
        $this->assertCount(0, $list);
    }

    #[Test]
    public function subListWithInvalidIndices(): void
    {
        $list = new ArrayList();
        $list->add(10);
        $list->add(20);
        $list->add(30);

        // Trying to get a sublist with invalid indices
        $this->expectException(\OutOfRangeException::class);
        $list->subList(2, 1);
    }

    #[Test]
    public function subList(): void
    {
        $list = new ArrayList();
        $list->add(10);
        $list->add(20);
        $list->add(30);
        $list->add(40);
        $list->add(50);

        // Get a sublist from index 1 to 4 (excluding index 4)
        $subList = $list->subList(1, 4);

        $this->assertCount(3, $subList);
        $this->assertEquals(20, $subList->get(0));
        $this->assertEquals(30, $subList->get(1));
        $this->assertEquals(40, $subList->get(2));

        // Get a sublist from index 2 to 3 (excluding index 3)
        $subList = $list->subList(2, 3);

        $this->assertCount(1, $subList);
        $this->assertEquals(30, $subList->get(0));
    }

    #[Test]
    public function replaceAll(): void
    {
        /** @var ArrayList<int> $list */
        $list = new ArrayList();
        $list->add(10);
        $list->add(20);
        $list->add(30);

        $list->replaceAll(function (int $element): int {
            return $element * 2;
        });

        $this->assertEquals(20, $list->get(0));
        $this->assertEquals(40, $list->get(1));
        $this->assertEquals(60, $list->get(2));
    }

    #[Test]
    public function removeAll(): void
    {
        $list1 = new ArrayList();
        $list1->add(10);
        $list1->add(20);
        $list1->add(30);

        $list2 = new ArrayList();
        $list2->add(20);
        $list2->add(40);

        $list1->removeAll($list2);

        $this->assertCount(2, $list1);
        $this->assertEquals(10, $list1->get(0));
        $this->assertEquals(30, $list1->get(1));
    }

    #[Test]
    public function addAll(): void
    {
        $list1 = new ArrayList();
        $list1->add(10);
        $list1->add(20);

        $list2 = new ArrayList();
        $list2->add(30);
        $list2->add(40);

        $list1->addAll($list2);

        $this->assertCount(4, $list1);
        $this->assertEquals(10, $list1->get(0));
        $this->assertEquals(20, $list1->get(1));
        $this->assertEquals(30, $list1->get(2));
        $this->assertEquals(40, $list1->get(3));
    }

    #[Test]
    public function containsAll(): void
    {
        /** @var ArrayList<int> $list1 */
        $list1 = new ArrayList();
        $list1->add(10);
        $list1->add(20);
        $list1->add(30);

        /** @var ArrayList<int> $list2 */
        $list2 = new ArrayList();
        $list2->add(10);
        $list2->add(20);

        /** @var ArrayList<int> $list3 */
        $list3 = new ArrayList();
        $list3->add(20);
        $list3->add(40);

        $this->assertTrue($list1->containsAll($list2));
        $this->assertFalse($list1->containsAll($list3));
    }

    #[Test]
    public function addByIndexWithInvalidIndex(): void
    {
        $list = new ArrayList();
        $list->add(10);

        // Adding at an invalid index
        $this->expectException(\OutOfRangeException::class);
        $list->addByIndex(5, 20);
    }

    #[Test]
    public function addByIndex(): void
    {
        $list = new ArrayList();
        $list->add(10);
        $list->add(20);
        $list->addByIndex(1, 15); // Inserts 15 between 10 and 20

        $this->assertEquals(10, $list->get(0));
        $this->assertEquals(15, $list->get(1));
        $this->assertEquals(20, $list->get(2));
    }

    #[Test]
    public function getWithInvalidIndex(): void
    {
        $list = new ArrayList();
        $list->add(10);

        // Getting element at an invalid index
        $this->expectException(\OutOfRangeException::class);
        $list->get(1);
    }

    #[Test]
    public function lastIndexOfWithNonExistentElement(): void
    {
        $list = new ArrayList();
        $list->add(10);
        $list->add(20);

        // Searching for a non-existent element
        $this->assertEquals(-1, $list->lastIndexOf(30));
    }

    #[Test]
    public function lastIndexOf(): void
    {
        $list = new ArrayList();
        $list->add(10);
        $list->add(20);
        $list->add(30);
        $list->add(20); // Adding 20 again

        $this->assertEquals(3, $list->lastIndexOf(20));
        $this->assertEquals(-1, $list->lastIndexOf(40));
    }

    #[Test]
    public function removeByIndexWithInvalidIndex(): void
    {
        $list = new ArrayList();
        $list->add(10);

        // Removing an element at an invalid index
        $this->expectException(\OutOfRangeException::class);
        $list->removeByIndex(1);
    }

    #[Test]
    public function removeByIndex(): void
    {
        /** @var ArrayList<int> $list */
        $list = new ArrayList();
        $list->add(10);
        $list->add(20);
        $list->add(30);

        $removedElement = $list->removeByIndex(1); // Removes the element at index 1 (20)

        $this->assertEquals(20, $removedElement);
        $this->assertEquals(10, $list->get(0));
        $this->assertEquals(30, $list->get(1));
    }

    #[Test]
    public function setWithInvalidIndex(): void
    {
        $list = new ArrayList();
        $list->add(10);

        // Setting an element at an invalid index
        $this->expectException(\OutOfRangeException::class);
        $list->set(1, 20);
    }

    #[Test]
    public function set(): void
    {
        /** @var ArrayList<int> $list */
        $list = new ArrayList();
        $list->add(10);
        $list->add(20);
        $list->add(30);

        $oldElement = $list->set(1, 25);

        $this->assertEquals(20, $oldElement);
        $this->assertEquals(25, $list->get(1));
    }

    #[Test]
    public function forEach(): void
    {
        /** @var ArrayList<int> $list */
        $list = new ArrayList();
        $list->add(10);
        $list->add(20);
        $list->add(30);


        $sum = 0;
        $list->forEach(function (int $element) use (&$sum): void {
            assert(is_int($sum));
            $sum += $element;
        });

        $this->assertEquals(60, $sum); // 10 + 20 + 30 = 60
    }

    #[Test]
    public function indexOfReturnsNegativeOneWhenNotFound(): void
    {
        $list = new ArrayList(10, 20);

        $this->assertEquals(-1, $list->indexOf(30));
        $this->assertEquals(0, $list->indexOf(10));
        $this->assertEquals(1, $list->indexOf(20));
    }
}

