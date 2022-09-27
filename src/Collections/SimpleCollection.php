<?php declare(strict_types=1);

namespace Envorra\ClassFinder\Collections;

use Countable;
use ArrayAccess;
use Traversable;
use ArrayIterator;
use IteratorAggregate;

/**
 * SimpleCollection
 *
 * @package Envorra\ClassFinder\Collections
 *
 * @template TKey of array-key
 * @template TItem
 *
 * @implements ArrayAccess<TKey, TItem>
 * @implements IteratorAggregate<TKey, TItem>
 */
class SimpleCollection implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * @param  TItem[]  $items
     */
    public function __construct(protected array $items = [])
    {

    }

    /**
     * @return TItem[]
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->offsetExists($offset) ? $this->items[$offset] : null;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($this->offsetExists($offset)) {
            $this->items[$offset] = $value;
        } else {
            $this->items[] = $value;
        }
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }

    /**
     * @param  TItem|null  $item
     * @return static
     */
    public function push(mixed $item): static
    {
        if ($item) {
            $this->items[] = $item;
        }
        return $this;
    }

    /**
     * @return TItem|null
     */
    public function first(): mixed
    {
        return $this->offsetGet(0);
    }

    /**
     * @param  int  $nth
     * @return TItem|null
     */
    public function nth(int $nth): mixed
    {
        return $this->offsetGet($nth);
    }

    /**
     * @return TItem|null
     */
    public function last(): mixed
    {
        $items = $this->items;
        return end($items);
    }
}

