<?php

namespace Envorra\ClassFinder\Factories;

use PhpParser\Node;
use ReflectionClass;
use Envorra\ClassFinder\Enums\Type;
use Envorra\ClassFinder\Contracts\Factory;

/**
 * TypeFactory
 *
 * @package Envorra\ClassFinder\Factories
 *
 * @implements Factory<Type>
 */
class TypeFactory implements Factory
{
    /**
     * @inheritDoc
     */
    public static function create(mixed $from = null): Type
    {
        return match (true) {
            $from instanceof Node => static::createFromNode($from),
            $from instanceof ReflectionClass => static::createFromReflection($from),
            is_object($from) => static::createFromObject($from),
            is_string($from) => static::createFromName($from),
            is_int($from) => static::createFromValue($from),
            default => static::createUnknown(),
        };
    }

    /**
     * @inheritDoc
     */
    public static function createOrFail(mixed $from = null): Type
    {
        return static::create($from);
    }

    /**
     * @param  string  $name
     * @return Type
     */
    protected static function createFromName(string $name): Type
    {
        return Type::fromName($name);
    }

    /**
     * @param  Node  $node
     * @return Type
     */
    protected static function createFromNode(Node $node): Type
    {
        return Type::fromNode($node);
    }

    /**
     * @param  object  $object
     * @return Type
     */
    protected static function createFromObject(object $object): Type
    {
        return static::createFromReflection(
            reflection: $object instanceof ReflectionClass ? $object : new ReflectionClass($object)
        );
    }

    /**
     * @param  ReflectionClass  $reflection
     * @return Type
     */
    protected static function createFromReflection(ReflectionClass $reflection): Type
    {
        return match (true) {
            $reflection->isTrait() => Type::TYPE_TRAIT,
            $reflection->isInterface() => Type::TYPE_INTERFACE,
            $reflection->isEnum() => Type::TYPE_ENUM,
            default => $reflection->isAbstract() ? Type::TYPE_ABSTRACT : Type::TYPE_CLASS,
        };
    }

    /**
     * @param  int  $value
     * @return Type
     */
    protected static function createFromValue(int $value): Type
    {
        return Type::tryFrom($value) ?? Type::TYPE_UNKNOWN;
    }

    /**
     * @return Type
     */
    protected static function createUnknown(): Type
    {
        return Type::TYPE_UNKNOWN;
    }
}
