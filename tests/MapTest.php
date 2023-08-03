<?php

declare(strict_types=1);

namespace VKPHPUtils\DS\Tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use VKPHPUtils\DS\Map;
use VKPHPUtils\DS\MapEntry;
use VKPHPUtils\DS\Tests\Objects\TestObject;
use VKPHPUtils\DS\Tests\Objects\ValuesHolder;

class MapTest extends TestCase
{
    #[Test]
    public function emptyMap(): void
    {
        $map = new Map();
        $this->assertTrue($map->isEmpty());
        $this->assertSame(0, $map->size());
    }

    #[Test]
    public function putAndGet(): void
    {
        $map = new Map();
        $map->put('key1', 'value1');
        $map->put('key2', 'value2');
        $map->put('key3', 'value3');

        $this->assertSame('value1', $map->get('key1'));
        $this->assertSame('value2', $map->get('key2'));
        $this->assertSame('value3', $map->get('key3'));
    }

    #[Test]
    public function putNullKey(): void
    {
        $map = new Map();
        $this->expectException(\InvalidArgumentException::class);
        $map->put(null, 'value1');
    }

    #[Test]
    public function putAndGetWithExistingKey(): void
    {
        $map = new Map();
        $map->put('key1', 'value1');
        $map->put('key1', 'updated_value');

        $this->assertSame('updated_value', $map->get('key1'));
    }

    #[Test]
    public function removeNonExistentKey(): void
    {
        $map = new Map();

        $removedValue = $map->remove('non_existent_key');
        $this->assertNull($removedValue);
    }

    #[Test]
    public function remove(): void
    {
        $map = new Map();
        $map->put('key1', 'value1');

        $this->assertTrue($map->containsKey('key1'));

        $removedValue = $map->remove('key1');
        $this->assertSame('value1', $removedValue);
        $this->assertFalse($map->containsKey('key1'));
        $this->assertNull($map->get('key1'));
    }

    #[Test]
    public function containsKey(): void
    {
        $map = new Map();
        $map->put('key1', 'value1');

        $this->assertTrue($map->containsKey('key1'));
        $this->assertFalse($map->containsKey('non_existent_key'));
    }

    #[Test]
    public function checkSize(): void
    {
        $map = new Map();
        $this->assertSame(0, $map->size());

        $map->put('key1', 'value1');
        $map->put('key2', 'value2');

        $this->assertSame(2, $map->size());
    }

    #[Test]
    public function checkClear(): void
    {
        $map = new Map();
        $map->put('key1', 'value1');
        $map->put('key2', 'value2');

        $map->clear();

        $this->assertTrue($map->isEmpty());
        $this->assertSame(0, $map->size());
    }

    #[Test]
    public function computeIfAbsentWithExistingKey(): void
    {
        /** @var Map<string, string> $map */
        $map = new Map();
        $map->put('key1', 'existing_value');

        $result = $map->computeIfAbsent('key1', function ($key) {
            return $key . '_value';
        });

        $this->assertSame('existing_value', $result);
    }

    #[Test]
    public function computeIfAbsent(): void
    {
        /** @var Map<string, string> $map */
        $map = new Map();

        $result = $map->computeIfAbsent('key1', function ($key) {
            return $key . '_value';
        });

        $this->assertSame('key1_value', $result);
        $this->assertTrue($map->containsKey('key1'));
    }

    #[Test]
    public function replaceNonExistentKey(): void
    {
        $map = new Map();

        $oldValue = $map->replace('non_existent_key', 'new_value');

        $this->assertNull($oldValue);
        $this->assertTrue($map->containsKey('non_existent_key'));
    }

    #[Test]
    public function replace(): void
    {
        $map = new Map();
        $map->put('key1', 'value1');

        $oldValue = $map->replace('key1', 'new_value');

        $this->assertSame('value1', $oldValue);
        $this->assertSame('new_value', $map->get('key1'));
    }

    #[Test]
    public function values(): void
    {
        $map = new Map();
        $map->put('key1', 'value1');
        $map->put('key2', 'value2');

        $values = $map->values();
        $this->assertCount(2, $values);

        $this->assertEquals(['value1', 'value2'], $values->toArray());
    }

    #[Test]
    public function containsValue(): void
    {
        $map = new Map();
        $map->put('key1', 'value1');
        $map->put('key2', 'value2');

        $this->assertTrue($map->containsValue('value1'));
        $this->assertTrue($map->containsValue('value2'));
        $this->assertFalse($map->containsValue('non_existent_value'));
    }

    #[Test]
    public function containsKeyReturnsFalse(): void
    {
        $map = new Map();
        $map->put('key1', 'value1');

        $this->assertFalse($map->containsKey('non_existent_key'));
    }

    #[Test]
    public function putAndGetWithObjects(): void
    {
        $map = new Map();

        // Create test objects
        $obj1 = $this->createTestObject('obj1');
        $obj2 = $this->createTestObject('obj2');

        $map->put($obj1, 'value1');
        $map->put($obj2, 'value2');

        $this->assertSame('value1', $map->get($obj1));
        $this->assertSame('value2', $map->get($obj2));
    }

    private function createTestObject(string $name): TestObject
    {
        return new TestObject($name);
    }

    #[Test]
    public function containsKeyWithObjects(): void
    {
        $map = new Map();

        $obj1 = $this->createTestObject('obj1');
        $obj2 = $this->createTestObject('obj2');

        $map->put($obj1, 'value1');

        $this->assertTrue($map->containsKey($obj1));
        $this->assertFalse($map->containsKey($obj2));
    }

    #[Test]
    public function removeWithObjects(): void
    {
        $map = new Map();

        $obj1 = $this->createTestObject('obj1');
        $obj2 = $this->createTestObject('obj2');

        $map->put($obj1, 'value1');
        $map->put($obj2, 'value2');

        $this->assertTrue($map->containsKey($obj1));

        $removedValue = $map->remove($obj1);
        $this->assertSame('value1', $removedValue);
        $this->assertFalse($map->containsKey($obj1));
        $this->assertNull($map->get($obj1));
    }

    #[Test]
    public function computeWithObjects(): void
    {
        /** @var Map<object, int> $map */
        $map = new Map();

        $obj1 = $this->createTestObject('obj1');

        $map->put($obj1, 10);

        $result = $map->compute($obj1, function (object $key, int|null $value): int|null {
            if ($value === null) {
                return null;
            }
            return $value * 2;
        });

        $this->assertSame(20, $result);

        $objWhichDoesNotExist = $this->createTestObject('non_existent');

        $nullExpectedResult = $map->compute($objWhichDoesNotExist, function (object $key, int|null $value): int|null {
            if ($value === null) {
                return null;
            }
            return $value * 2;
        });

        $this->assertNull($nullExpectedResult);
    }

    #[Test]
    public function compute(): void
    {
        /** @var Map<string, int> $map */
        $map = new Map();
        $map->put('key1', 10);

        $result = $map->compute('key1', function ($key, int|null $value): int|null {
            if ($value === null) {
                return null;
            }
            return $value * 2;
        });

        $this->assertSame(20, $result);

        $nullExpectedResult = $map->compute('non_existent_key', function ($key, int|null $value): int|null {
            if ($value === null) {
                return null;
            }
            return $value * 2;
        });

        $this->assertNull($nullExpectedResult);

        // check that element removed from the map
        $result = $map->compute('key1', fn($_, $__) => null);
        $this->assertNull($result);
        $this->assertFalse($map->containsKey('key1'));
    }

    #[Test]
    public function computeIfAbsentWithObjects(): void
    {
        /** @var Map<TestObject, string> $map */
        $map = new Map();

        $obj1 = $this->createTestObject('obj1');

        $result = $map->computeIfAbsent($obj1, function (TestObject $key): string {
            return $key->name . '_value';
        });

        $this->assertSame('obj1_value', $result);
        $this->assertTrue($map->containsKey($obj1));
    }

    #[Test]
    public function computeIfPresentWithObjects(): void
    {
        /** @var Map<TestObject, int> $map */
        $map = new Map();

        $obj1 = $this->createTestObject('obj1');

        $map->put($obj1, 10);

        $result = $map->computeIfPresent($obj1, function ($key, $value): int {
            return $value * 2;
        });

        $this->assertSame(20, $result);

        // check that element removed from the map
        $this->assertNotNull($map->get($obj1));
        $map->computeIfPresent($obj1, function ($_, $__): null {
            return null;
        });
        $this->assertFalse($map->containsKey($obj1));
    }

    #[Test]
    public function computeIfPresent(): void
    {
        /** @var Map<string, int> $map */
        $map = new Map();
        $map->put('key1', 10);

        $result = $map->computeIfPresent('key1', function ($key, $value): int {
            return $value * 2;
        });

        $this->assertSame(20, $result);
    }

    #[Test]
    public function replaceWithObjects(): void
    {
        $map = new Map();

        $obj1 = $this->createTestObject('obj1');

        $map->put($obj1, 'value1');

        $oldValue = $map->replace($obj1, 'new_value');

        $this->assertSame('value1', $oldValue);
        $this->assertSame('new_value', $map->get($obj1));
    }

    #[Test]
    public function replaceAllWithObjects(): void
    {
        /** @var Map<TestObject, int> $map */
        $map = new Map();

        $obj1 = $this->createTestObject('obj1');
        $obj2 = $this->createTestObject('obj2');
        $obj3 = $this->createTestObject('obj3');

        $map->put($obj1, 10);
        $map->put($obj2, 20);
        $map->put($obj3, 30);

        $map->replaceAll(function ($key, int|null $value): int|null {
            if ($value === null) {
                return null;
            }
            return $value * 2;
        });

        $this->assertSame(20, $map->get($obj1));
        $this->assertSame(40, $map->get($obj2));
        $this->assertSame(60, $map->get($obj3));
    }

    #[Test]
    public function replaceAll(): void
    {
        /** @var Map<string, int> $map */
        $map = new Map();
        $map->put('key1', 10);
        $map->put('key2', 20);
        $map->put('key3', 30);

        $map->replaceAll(function (string $key, int|null $value): int|null {
            if ($value === null) {
                return null;
            }
            return $value * 2;
        });

        $this->assertSame(20, $map->get('key1'));
        $this->assertSame(40, $map->get('key2'));
        $this->assertSame(60, $map->get('key3'));
    }

    #[Test]
    public function getOrDefaultWithObjects(): void
    {
        $map = new Map();

        $obj1 = $this->createTestObject('obj1');

        $map->put($obj1, 'value1');

        $this->assertSame('value1', $map->getOrDefault($obj1, 'default_value'));
        $this->assertSame(
            'default_value',
            $map->getOrDefault($this->createTestObject('non_existent_key'), 'default_value')
        );
    }

    #[Test]
    public function getOrDefault(): void
    {
        $map = new Map();
        $map->put('key1', 'value1');

        $this->assertSame('value1', $map->getOrDefault('key1', 'default_value'));
        $this->assertSame('default_value', $map->getOrDefault('non_existent_key', 'default_value'));
    }

    #[Test]
    public function keySetWithObjects(): void
    {
        $map = new Map();

        $obj1 = $this->createTestObject('obj1');
        $obj2 = $this->createTestObject('obj2');

        $map->put($obj1, 'value1');
        $map->put($obj2, 'value2');

        $keySet = $map->keySet();
        $keys = $keySet->toArray();

        $this->assertCount(2, $keys);
        $this->assertTrue($keySet->contains($obj1));
        $this->assertTrue($keySet->contains($obj2));
    }

    #[Test]
    public function keySet(): void
    {
        $map = new Map();
        $map->put('key1', 'value1');
        $map->put('key2', 'value2');

        $keySet = $map->keySet();
        $keys = $keySet->toArray();

        $this->assertSame(['key1', 'key2'], $keys);
    }

    #[Test]
    public function putAllWithObjects(): void
    {
        $sourceMap = new Map();

        $obj1 = $this->createTestObject('obj1');
        $obj2 = $this->createTestObject('obj2');

        $sourceMap->put($obj1, 'value1');
        $sourceMap->put($obj2, 'value2');

        $targetMap = new Map();
        $targetMap->put('key3', 'value3');

        $targetMap->putAll($sourceMap);

        $this->assertTrue($targetMap->containsKey($obj1));
        $this->assertTrue($targetMap->containsKey($obj2));
        $this->assertTrue($targetMap->containsKey('key3'));
    }

    #[Test]
    public function putAll(): void
    {
        $sourceMap = new Map();
        $sourceMap->put('key1', 'value1');
        $sourceMap->put('key2', 'value2');

        $targetMap = new Map();
        $targetMap->put('key3', 'value3');

        $targetMap->putAll($sourceMap);

        $this->assertTrue($targetMap->containsKey('key1'));
        $this->assertTrue($targetMap->containsKey('key2'));
        $this->assertTrue($targetMap->containsKey('key3'));
    }

    #[Test]
    public function entrySetWithObjects(): void
    {
        $map = new Map();

        $obj1 = $this->createTestObject('obj1');
        $obj2 = $this->createTestObject('obj2');

        $map->put($obj1, 'value1');
        $map->put($obj2, 'value2');

        $entries = $map->entrySet()->toArray();

        $this->assertCount(2, $entries);
        $this->assertInstanceOf(MapEntry::class, $entries[0]);
        $this->assertSame($obj1, $entries[0]->key);
        $this->assertSame('value1', $entries[0]->value);
        $this->assertInstanceOf(MapEntry::class, $entries[1]);
        $this->assertSame($obj2, $entries[1]->key);
        $this->assertSame('value2', $entries[1]->value);
    }

    #[Test]
    public function entrySet(): void
    {
        $map = new Map();
        $map->put('key1', 'value1');
        $map->put('key2', 'value2');

        $entries = $map->entrySet()->toArray();

        $this->assertCount(2, $entries);
        $this->assertInstanceOf(MapEntry::class, $entries[0]);
        $this->assertSame('key1', $entries[0]->key);
        $this->assertSame('value1', $entries[0]->value);
        $this->assertInstanceOf(MapEntry::class, $entries[1]);
        $this->assertSame('key2', $entries[1]->key);
        $this->assertSame('value2', $entries[1]->value);
    }

    #[Test]
    public function removeEntryWithObjects(): void
    {
        $map = new Map();

        $obj1 = $this->createTestObject('obj1');
        $obj2 = $this->createTestObject('obj2');

        $map->put($obj1, 'value1');
        $map->put($obj2, 'value2');

        $this->assertTrue($map->containsKey($obj1));

        $result = $map->removeEntry($obj1, 'non_existent_value');
        $this->assertFalse($result);
        $this->assertTrue($map->containsKey($obj1));

        $result = $map->removeEntry($obj1, 'value1');
        $this->assertTrue($result);
        $this->assertFalse($map->containsKey($obj1));
    }

    #[Test]
    public function removeEntry(): void
    {
        $map = new Map();
        $map->put('key1', 'value1');

        $this->assertTrue($map->containsKey('key1'));

        $result = $map->removeEntry('key1', 'non_existent_value');
        $this->assertFalse($result);
        $this->assertTrue($map->containsKey('key1'));

        $result = $map->removeEntry('key1', 'value1');
        $this->assertTrue($result);
        $this->assertFalse($map->containsKey('key1'));
    }

    #[Test]
    public function forEachWithObjects(): void
    {
        /** @var Map<TestObject, string> $map */
        $map = new Map();

        $obj1 = $this->createTestObject('obj1');
        $obj2 = $this->createTestObject('obj2');
        $obj3 = $this->createTestObject('obj3');

        $map->put($obj1, 'value1');
        $map->put($obj2, 'value2');
        $map->put($obj3, 'value3');

        $expectedKeys = [$obj1, $obj2, $obj3];
        $expectedValues = ['value1', 'value2', 'value3'];

        $keys = new ValuesHolder();
        $values = new ValuesHolder();

        // Using a closure to capture $keys and $values by reference
        $map->forEach(function (TestObject $key, string|null $value) use ($keys, $values): void {
            $keys->append($key);
            $values->append($value);
        });

        $this->assertSame($expectedKeys, $keys->getValues());
        $this->assertSame($expectedValues, $values->getValues());
    }

    #[Test]
    public function forEach(): void
    {
        /** @var Map<string, string> $map */
        $map = new Map();
        $map->put('key1', 'value1');
        $map->put('key2', 'value2');
        $map->put('key3', 'value3');

        $expectedKeys = ['key1', 'key2', 'key3'];
        $expectedValues = ['value1', 'value2', 'value3'];

        $keys = new ValuesHolder();
        $values = new ValuesHolder();

        // Using a closure to capture $keys and $values by reference
        $map->forEach(function ($key, $value) use ($keys, $values) {
            $keys->append($key);
            $values->append($value);
        });

        $this->assertSame($expectedKeys, $keys->getValues());
        $this->assertSame($expectedValues, $values->getValues());
    }

    #[Test]
    public function containsKeyReturnsFalseWithObjects(): void
    {
        $map = new Map();

        $obj1 = $this->createTestObject('obj1');
        $obj2 = $this->createTestObject('obj2');

        $map->put($obj1, 'value1');

        $this->assertFalse($map->containsKey($obj2));
    }

    #[Test]
    public function checkArrayAccess(): void
    {
        /** @var Map<string, string> $map */
        $map = new Map();

        // check put
        $map[$key = 'key1'] = 'value1';

        $this->assertSame(1, $map->size());
        $this->assertCount(1, $map->values());
        $this->assertCount(1, $map->keySet());
        $this->assertCount(1, $map->entrySet());

        // check get
        $this->assertSame('value1', $map[$key]);
        $this->assertNull($map['unknown']);

        // check isset
        $this->assertTrue(isset($map[$key]));
        $this->assertFalse(isset($map['unknown']));

        // check unset
        unset($map[$key]);
        $this->assertSame(0, $map->size());
        $this->assertCount(0, $map->values());
        $this->assertCount(0, $map->keySet());
        $this->assertCount(0, $map->entrySet());
        $this->assertFalse(isset($map[$key]));

        // expected exception
        $this->expectException(\InvalidArgumentException::class);
        /**
         * @psalm-suppress NullArgument
         * @psalm-suppress NullArrayOffset
         */
        $map[null] = 'value1';

    }
}
