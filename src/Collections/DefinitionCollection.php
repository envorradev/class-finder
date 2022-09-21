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
 * @package  Envorra\ClassFinder\Collections
 *
 * @template T of TypeDefinition
 *
 * @implements ArrayAccess<array-key, T>
 * @implements IteratorAggregate<array-key, T>
 */
class DefinitionCollection implements Countable, ArrayAccess, IteratorAggregate
{
    /**
     * @param  T[]  $items
     */
    public function __construct(protected array $items = [])
    {

    }

    /**
     * @return T[]
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
    public function offsetGet(mixed $offset): ?TypeDefinition
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
     * @param  T|null  $definition
     * @return static
     */
    public function push(?TypeDefinition $definition): static
    {
        if ($definition) {
            $this->items[] = $definition;
        }
        return $this;
    }

    /**
     * @return T|null
     */
    public function first(): ?TypeDefinition
    {
        return $this->offsetGet(0);
    }

    /**
     * @param  int  $nth
     * @return T|null
     */
    public function nth(int $nth): ?TypeDefinition
    {
        return $this->offsetGet($nth);
    }

    /**
     * @return T|null
     */
    public function last(): ?TypeDefinition
    {
        $items = $this->items;
        return end($items);
    }
}
