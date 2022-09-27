<?php

namespace Envorra\ClassFinder\Factories;

use SplFileInfo;
use ReflectionClass;
use PhpParser\Node\Stmt\ClassLike;
use Envorra\ClassFinder\Contracts\Factory;
use Envorra\ClassFinder\Exceptions\FactoryException;
use Envorra\ClassFinder\DefinitionBuilders\BuildFromFile;
use Envorra\ClassFinder\DefinitionBuilders\BuildFromObject;
use Envorra\ClassFinder\DefinitionBuilders\BuildFromString;
use Envorra\ClassFinder\Contracts\Definitions\TypeDefinition;
use Envorra\ClassFinder\DefinitionBuilders\BuildFromReflection;
use Envorra\ClassFinder\DefinitionBuilders\BuildFromClassLikeNode;

/**
 * DefinitionFactory
 *
 * @package Envorra\ClassFinder\Factories
 *
 * @implements Factory<TypeDefinition>
 */
class DefinitionFactory implements Factory
{
    /**
     * @inheritDoc
     */
    public static function create(mixed $from = null): ?TypeDefinition
    {
        try {
            return static::createOrFail($from);
        } catch (FactoryException) {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public static function createOrFail(mixed $from = null): TypeDefinition
    {
        return match (gettype($from)) {
            'object' => static::fromObject($from),
            'string' => static::fromString($from),
            default => FactoryException::throwFailure(),
        };
    }

    /**
     * @param  ClassLike  $node
     * @return TypeDefinition
     */
    protected static function fromClassLikeNode(ClassLike $node): TypeDefinition
    {
        return BuildFromClassLikeNode::make($node);
    }

    /**
     * @param  SplFileInfo  $file
     * @return TypeDefinition
     * @throws FactoryException
     */
    protected static function fromFile(SplFileInfo $file): TypeDefinition
    {
        return BuildFromFile::make($file) ?? FactoryException::throwFailure();
    }

    /**
     * @param  object  $object
     * @return TypeDefinition
     * @throws FactoryException
     */
    protected static function fromObject(object $object): TypeDefinition
    {
        return match (true) {
            $object instanceof TypeDefinition => $object,
            $object instanceof ClassLike => static::fromClassLikeNode($object),
            $object instanceof SplFileInfo => static::fromFile($object),
            $object instanceof ReflectionClass => static::fromReflection($object),
            default => BuildFromObject::make($object) ?? FactoryException::throwFailure(),
        };
    }

    /**
     * @param  ReflectionClass  $reflection
     * @return TypeDefinition
     * @throws FactoryException
     */
    protected static function fromReflection(ReflectionClass $reflection): TypeDefinition
    {
        return BuildFromReflection::make($reflection) ?? FactoryException::throwFailure();
    }

    /**
     * @param  string  $string
     * @return TypeDefinition
     * @throws FactoryException
     */
    protected static function fromString(string $string): TypeDefinition
    {
        return BuildFromString::make($string) ?? FactoryException::throwFailure();
    }
}
