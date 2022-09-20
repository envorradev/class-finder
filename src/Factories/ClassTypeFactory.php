<?php

namespace Envorra\ClassFinder\Factories;

use PhpParser\Node;
use Envorra\ClassFinder\Enums\Type;
use Envorra\ClassFinder\Contracts\ClassType;

/**
 * ClassTypeFactory
 *
 * @package Envorra\ClassFinder\Factories
 */
class ClassTypeFactory
{
    /**
     * @param  Node|string|int|null  $from
     * @return ClassType
     */
    public static function create(Node|string|int|null $from = null): ClassType
    {
        if ($from instanceof Node) {
            return static::createFromNode($from);
        }

        if (is_string($from)) {
            return static::createFromName($from);
        }

        if (is_int($from)) {
            return static::createFromValue($from);
        }

        return static::createUnknown();
    }

    /**
     * @param  string  $name
     * @return ClassType
     */
    public static function createFromName(string $name): ClassType
    {
        return Type::fromName($name);
    }

    /**
     * @param  Node  $node
     * @return ClassType
     */
    public static function createFromNode(Node $node): ClassType
    {
        return Type::fromNode($node);
    }

    /**
     * @param  int  $value
     * @return ClassType
     */
    public static function createFromValue(int $value): ClassType
    {
        return Type::tryFrom($value) ?? Type::TYPE_UNKNOWN;
    }

    /**
     * @return ClassType
     */
    public static function createUnknown(): ClassType
    {
        return Type::TYPE_UNKNOWN;
    }
}
