<?php

namespace Envorra\ClassFinder\Factories;

use SplFileInfo;
use ReflectionClass;
use ReflectionException;
use PhpParser\Node\Stmt\ClassLike;
use Envorra\ClassFinder\FileHandler;
use Envorra\ClassFinder\Contracts\Resolver;
use Envorra\ClassFinder\Contracts\ClassType;
use Envorra\ClassFinder\Definitions\Definition;
use Envorra\ClassFinder\Definitions\EnumDefinition;
use Envorra\ClassFinder\Definitions\ClassDefinition;
use Envorra\ClassFinder\Definitions\TraitDefinition;
use Envorra\ClassFinder\Definitions\AbstractDefinition;
use Envorra\ClassFinder\Definitions\InterfaceDefinition;
use Envorra\ClassFinder\Contracts\Definitions\TypeDefinition;

/**
 * DefinitionFactory
 *
 * @package Envorra\ClassFinder\Factories
 */
class DefinitionFactory
{
    /**
     * @param  ClassLike      $node
     * @param  Resolver|null  $resolver
     * @return TypeDefinition
     */
    public static function createFromClassLikeNode(ClassLike $node, ?Resolver $resolver = null): TypeDefinition
    {
        $type = ClassTypeFactory::createFromNode($node);
        $definition = static::getDefinitionClass($type);

        return new $definition(
            node: $node,
            resolver: $resolver ?? ResolverFactory::create(),
            type: $type
        );
    }

    /**
     * @param  string|null  $class
     * @return TypeDefinition|null
     */
    public static function tryFromClassName(?string $class): ?TypeDefinition
    {
        if (!$class) {
            return null;
        }

        try {
            $reflection = new ReflectionClass($class);
            return static::tryFromFileName($reflection->getFileName() ?: '');
        } catch (ReflectionException) {
            return null;
        }
    }

    /**
     * @param  ?SplFileInfo  $file
     * @return TypeDefinition|null
     */
    public static function tryFromFile(?SplFileInfo $file): ?TypeDefinition
    {
        if (!$file) {
            return null;
        }

        $handler = new FileHandler($file);
        $handler->traverse();
        return $handler->definition;
    }

    /**
     * @param  ?string  $fileName
     * @return TypeDefinition|null
     */
    public static function tryFromFileName(?string $fileName): ?TypeDefinition
    {
        if (!$fileName) {
            return null;
        }

        $file = new SplFileInfo($fileName);

        if (!$file->isFile()) {
            return null;
        }

        return static::tryFromFile($file);
    }

    /**
     * @param  ?object  $object
     * @return TypeDefinition|null
     */
    public static function tryFromObject(?object $object): ?TypeDefinition
    {
        if (!$object) {
            return null;
        }

        return static::tryFromClassName($object::class);
    }

    /**
     * @template TType of TypeDefinition
     *
     * @param  mixed                $from
     * @param  class-string<TType>  $definitionClass
     * @return TType|null
     */
    public static function tryMatchDefinitionClass(mixed $from, string $definitionClass): ?TypeDefinition
    {
        if (!$from) {
            return null;
        }

        if ($from instanceof $definitionClass && $from instanceof TypeDefinition) {
            return $from;
        }

//        dump(gettype($from));
//        dump($from);
//        return null;

        $definition = match (gettype($from)) {
            'object' => $from instanceof SplFileInfo ? static::tryFromFile($from) : null,
            'string' => static::tryFromClassName($from) ?? static::tryFromFileName($from),
            default => null,
        };

        if ($definition instanceof $definitionClass && $definition instanceof TypeDefinition) {
            return $definition;
        }

        return null;
    }

    /**
     * @param  ClassType  $type
     * @return class-string<TypeDefinition>
     */
    protected static function getDefinitionClass(ClassType $type): string
    {
        return match (strtoupper($type->getShortName())) {
            'CLASS' => ClassDefinition::class,
            'ABSTRACT' => AbstractDefinition::class,
            'INTERFACE' => InterfaceDefinition::class,
            'ENUM' => EnumDefinition::class,
            'TRAIT' => TraitDefinition::class,
            default => Definition::class,
        };
    }
}
