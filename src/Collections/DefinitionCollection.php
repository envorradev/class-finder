<?php

namespace Envorra\ClassFinder\Collections;

use Countable;
use ArrayAccess;
use Traversable;
use ArrayIterator;
use IteratorAggregate;
use Envorra\ClassFinder\Contracts\Definitions\TypeDefinition;

/**
 * DefinitionCollection
 *
 * @package Envorra\ClassFinder\Collections
 *
 * @template TKey of array-key
 * @template TDefinition of TypeDefinition
 *
 * @implements ArrayAccess<TKey, TDefinition>
 * @implements IteratorAggregate<TKey, TDefinition>
 */
class DefinitionCollection implements Countable, ArrayAccess, IteratorAggregate
{
    /**
     * @param  TDefinition[]  $items
     */
    public function __construct(protected array $items = [])
    {

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
    public function offsetGet(mixed $offset): ?TypeDefinition
    {
        return $this->offsetExists($offset) ? $this->items[$offset] : null;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if($this->offsetExists($offset)) {
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
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->items);
    }


}
