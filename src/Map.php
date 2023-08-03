<?php

declare(strict_types=1);

namespace VKPHPUtils\DS;

use ArrayAccess;
use Closure;
use InvalidArgumentException;

/**
 * An object that maps keys to values. A map cannot contain duplicate keys; each key can map to at most one value.
 * The Map interface provides three collection views, which allow a map's contents to be viewed as a set of keys,
 *  collection of values, or set of key-value mappings.
 *
 * @template TKey
 * @template TValue
 *
 * @template-implements ArrayAccess<TKey, TValue>
 */
class Map implements ArrayAccess
{
    /** @var array<TKey> */
    private array $keys;

    /** @var array<TValue|null> */
    private array $values;

    /**
     * @param Map<TKey, TValue>|null $map
     */
    public function __construct(Map|null $map = null)
    {
        $this->keys = $map?->keys ?? [];
        $this->values = $map?->values ?? [];
    }


    /**
     * Removes all of the mappings from this map.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->keys = [];
        $this->values = [];
    }

    /**
     * Attempts to compute a mapping for the specified key and its current mapped value
     *  (or null if there is no current mapping).
     * If the function returns null, the mapping is removed (or remains absent if initially absent).
     * If the function itself throws an exception, the exception is rethrown, and the current mapping is left unchanged.
     *
     * @param TKey $key
     * @param Closure(TKey, ?TValue): ?TValue $remappingFunction
     * @return TValue|null
     */
    public function compute(mixed $key, Closure $remappingFunction): mixed
    {
        $oldValue = $this->get($key);
        $newValue = $remappingFunction($key, $oldValue);
        if ($newValue !== null) {
            $this->put($key, $newValue);
            return $newValue;
        }

        if ($oldValue !== null) {
            $this->remove($key);
            return null;
        }

        return null;
    }

    /**
     * Returns the value to which the specified key is mapped, or null if this map contains no mapping for the key.
     *
     * @param TKey $key
     * @return TValue|null
     */
    public function get(mixed $key): mixed
    {
        $idx = array_search($key, $this->keys);
        if ($idx !== false) {
            return $this->values[$idx];
        }

        return null;
    }

    /**
     * Associates the specified value with the specified key in this map.
     *
     * @param TKey $key key with which the specified value is to be associated
     * @param TValue|null $value value to be associated with the specified key
     * @return TValue|null the previous value associated with key, or null if there was no mapping for key.
     *  (A null return can also indicate that the map previously associated null with key)
     * @throws InvalidArgumentException if key is null
     */
    public function put(mixed $key, mixed $value): mixed
    {
        if ($key === null) {
            throw new InvalidArgumentException('Cannot put null key');
        }
        $keyIndex = array_search($key, $this->keys);
        $oldValue = $this->values[$keyIndex] ?? null;
        if ($keyIndex === false) {
            $keyIndex = $this->size();
            $this->keys[$keyIndex] = $key;
        }

        $this->values[$keyIndex] = $value;
        return $oldValue;
    }

    /**
     * Returns the number of key-value mappings in this map.
     *
     * @return int
     */
    public function size(): int
    {
        return count($this->keys);
    }

    /**
     * Removes the mapping for a key from this map if it is present (optional operation).
     *
     * @param TKey $key
     * @return TValue|null the previous value associated with key, or null if there was no mapping for key.
     */
    public function remove(mixed $key): mixed
    {
        $keyIndex = array_search($key, $this->keys);
        if ($keyIndex === false) {
            return null;
        }

        $oldValue = $this->values[$keyIndex];
        unset($this->keys[$keyIndex], $this->values[$keyIndex]);
        return $oldValue;
    }

    /**
     * If the specified key is not already associated with a value (or is mapped to null),
     *  attempts to compute its value using the given mapping function and enters it into this map unless null.
     *
     * @param TKey $key
     * @param Closure(TKey): ?TValue $mappingFunction
     * @return TValue|null the current (existing or computed) value associated with the specified key,
     *  or null if the computed value is null
     */
    public function computeIfAbsent(mixed $key, Closure $mappingFunction): mixed
    {
        $value = $this->get($key);
        if ($value === null) {
            $value = $mappingFunction($key);
            if ($value !== null) {
                $this->put($key, $value);
            }
        }

        return $value;
    }

    /**
     * If the value for the specified key is present and non-null, attempts to compute
     *  a new mapping given the key and its current mapped value.
     *
     * @param TKey $key
     * @param Closure(TKey, TValue): ?TValue $remappingFunction
     * @return TValue|null the new value associated with the specified key, or null if none
     */
    public function computeIfPresent(mixed $key, Closure $remappingFunction): mixed
    {
        $oldValue = $this->get($key);
        if ($oldValue !== null) {
            $newValue = $remappingFunction($key, $oldValue);
            if ($newValue !== null) {
                $this->put($key, $newValue);
                return $newValue;
            }

            $this->remove($key);
        }

        return null;
    }

    /**
     * Returns true if this map maps one or more keys to the specified value.
     *
     * @param mixed $value
     * @return bool
     */
    public function containsValue(mixed $value): bool
    {
        return in_array($value, $this->values);
    }

    /**
     * Performs the given action for each entry in this map until all entries have been processed
     *  or the action throws an exception.
     *
     * @param Closure(TKey, ?TValue): void $action
     * @return void
     */
    public function forEach(Closure $action): void
    {
        foreach ($this->keys as $idx => $key) {
            $action($key, $this->values[$idx]);
        }
    }

    /**
     * Returns the value to which the specified key is mapped,
     *  or defaultValue if this map contains no mapping for the key.
     *
     * @param TKey $key
     * @param TValue $defaultValue
     * @return TValue
     */
    public function getOrDefault(mixed $key, mixed $defaultValue): mixed
    {
        return $this->get($key) ?? $defaultValue;
    }

    /**
     * Returns true if this map contains no key-value mappings.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->size() === 0;
    }

    /**
     * Returns a {@link Set} view of the keys contained in this map.
     *
     * @return Set<TKey>
     */
    public function keySet(): Set
    {
        return new Set(...$this->keys);
    }

    /**
     * Copies all of the mappings from the specified map to this map (optional operation).
     *
     * @param Map<TKey, TValue> $map
     * @return void
     */
    public function putAll(Map $map): void
    {
        foreach ($map->entrySet() as $entry) {
            $this->put($entry->key, $entry->value);
        }
    }

    /**
     * Returns a Set view of the mappings contained in this map.
     *
     * @return Set<MapEntry<TKey, TValue>>
     */
    public function entrySet(): Set
    {
        /** @var Set<MapEntry<TKey, TValue>> $entrySet */
        $entrySet = new Set();
        foreach ($this->keys as $idx => $key) {
            $entrySet->add(new MapEntry($key, $this->values[$idx]));
        }
        return $entrySet;
    }

    /**
     * Removes the entry for the specified key only if it is currently mapped to the specified value.
     *
     * @param TKey $key key with which the specified value is associated
     * @param TValue $value value expected to be associated with the specified key
     * @return bool true if the value was removed
     */
    public function removeEntry(mixed $key, mixed $value): bool
    {
        if ($this->containsKey($key) && $this->get($key) == $value) {
            $this->remove($key);
            return true;
        }

        return false;
    }

    /**
     * Returns true if this map contains a mapping for the specified key.
     *
     * @param mixed $key
     * @return bool
     */
    public function containsKey(mixed $key): bool
    {
        return in_array($key, $this->keys);
    }

    /**
     * Replaces each entry's value with the result of invoking the given function on that entry
     *  until all entries have been processed or the function throws an exception.
     *
     * @param Closure(TKey, ?TValue): ?TValue $function
     * @return void
     */
    public function replaceAll(Closure $function): void
    {
        foreach ($this->keys as $idx => $key) {
            $this->replace($key, $function($key, $this->values[$idx]));
        }
    }

    /**
     * Replaces the entry for the specified key only if it is currently mapped to some value.
     *
     * @param TKey $key
     * @param TValue|null $value
     * @return TValue|null the previous value associated with the specified key, or null
     *  if there was no mapping for the key. (A null return can also indicate that the map
     *  previously associated null with the key)
     */
    public function replace(mixed $key, mixed $value): mixed
    {
        $oldValue = $this->get($key);
        $this->put($key, $value);
        return $oldValue;
    }

    /**
     * Returns a {@link Collection} view of the values contained in this map.
     *
     * @return Collection<TValue|null>
     */
    public function values(): Collection
    {
        return new ArrayList(...$this->values);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->containsKey($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            throw new InvalidArgumentException('Cannot put null key');
        }
        $this->put($offset, $value);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset(mixed $offset): void
    {
        $this->remove($offset);
    }
}
