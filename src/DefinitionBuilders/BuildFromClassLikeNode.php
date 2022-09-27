<?php declare(strict_types=1);

namespace Envorra\ClassFinder\DefinitionBuilders;

use PhpParser\Node\Stmt\ClassLike;
use Envorra\ClassFinder\Enums\Type;
use Envorra\ClassFinder\Contracts\Resolver;
use Envorra\ClassFinder\Factories\TypeFactory;
use Envorra\ClassFinder\Definitions\Definition;
use Envorra\ClassFinder\Factories\ResolverFactory;
use Envorra\ClassFinder\Definitions\EnumDefinition;
use Envorra\ClassFinder\Contracts\DefinitionBuilder;
use Envorra\ClassFinder\Definitions\ClassDefinition;
use Envorra\ClassFinder\Definitions\TraitDefinition;
use Envorra\ClassFinder\Definitions\AbstractDefinition;
use Envorra\ClassFinder\Definitions\InterfaceDefinition;
use Envorra\ClassFinder\Contracts\Definitions\TypeDefinition;
use Envorra\ClassFinder\Exceptions\DefinitionBuilderException;

/**
 * BuildFromClassLikeNode
 *
 * @package Envorra\ClassFinder\DefinitionBuilders
 */
final class BuildFromClassLikeNode implements DefinitionBuilder
{
    /**
     * @var TypeDefinition
     */
    protected TypeDefinition $definition;

    /**
     * @var Resolver
     */
    protected Resolver $resolver;

    /**
     * @var Type
     */
    protected Type $type;

    /**
     * @param  ClassLike      $node
     * @param  Resolver|null  $resolver
     */
    public function __construct(public readonly ClassLike $node, ?Resolver $resolver = null)
    {
        $this->resolver = $resolver ?? ResolverFactory::create();
        $this->type = TypeFactory::create($this->node);
    }

    /**
     * @param  ClassLike      $node
     * @param  Resolver|null  $resolver
     * @return static
     */
    public static function instance(ClassLike $node, ?Resolver $resolver = null): self
    {
        return new self($node, $resolver);
    }

    /**
     * @param  ClassLike      $node
     * @param  Resolver|null  $resolver
     * @return TypeDefinition|null
     */
    public static function make(ClassLike $node, ?Resolver $resolver = null): ?TypeDefinition
    {
        return self::instance($node, $resolver)->getDefinition();
    }

    /**
     * @inheritDoc
     */
    public function build(): self
    {
        $definitionClass = match ($this->type) {
            Type::TYPE_CLASS => ClassDefinition::class,
            Type::TYPE_ABSTRACT => AbstractDefinition::class,
            Type::TYPE_INTERFACE => InterfaceDefinition::class,
            Type::TYPE_ENUM => EnumDefinition::class,
            Type::TYPE_TRAIT => TraitDefinition::class,
            default => Definition::class,
        };

        $this->definition = new $definitionClass(
            node: $this->node,
            resolver: $this->resolver,
            type: $this->type,
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDefinition(): ?TypeDefinition
    {
        if (!isset($this->definition)) {
            try {
                $this->build();
            } catch (DefinitionBuilderException) {
                return null;
            }
        }
        return $this->definition;
    }
}
