<?php declare(strict_types=1);

namespace Envorra\ClassFinder\DefinitionBuilders;

use ReflectionClass;
use PhpParser\Builder;
use PhpParser\Builder\Enum_;
use PhpParser\BuilderFactory;
use PhpParser\Builder\Class_;
use PhpParser\Builder\Trait_;
use PhpParser\Builder\Interface_;
use PhpParser\Builder\Namespace_;
use PhpParser\Node\Stmt\ClassLike;
use Envorra\ClassFinder\Enums\Type;
use Envorra\ClassFinder\Contracts\Resolver;
use Envorra\ClassFinder\Factories\TypeFactory;
use Envorra\ClassFinder\Factories\ResolverFactory;
use Envorra\ClassFinder\Contracts\DefinitionBuilder;
use Envorra\ClassFinder\Contracts\Definitions\TypeDefinition;
use Envorra\ClassFinder\Exceptions\DefinitionBuilderException;

/**
 * BuildFromReflection
 *
 * @package Envorra\ClassFinder\DefinitionBuilders
 */
final class BuildFromReflection implements DefinitionBuilder
{
    /**
     * @var Type
     */
    public readonly Type $type;

    /**
     * @var TypeDefinition
     */
    protected TypeDefinition $definition;

    /**
     * @param  ReflectionClass  $reflection
     */
    public function __construct(public readonly ReflectionClass $reflection)
    {
        $this->type = TypeFactory::create($this->reflection);
    }

    /**
     * @param  ReflectionClass  $reflection
     * @return self
     */
    public static function instance(ReflectionClass $reflection): self
    {
        return new self($reflection);
    }

    /**
     * @param  ReflectionClass  $reflection
     * @return TypeDefinition|null
     */
    public static function make(ReflectionClass $reflection): ?TypeDefinition
    {
        return self::instance($reflection)->getDefinition();
    }

    /**
     * @inheritDoc
     */
    public function build(): self
    {
        $node = $this->buildNode()->getNode();

        if (!$node instanceof ClassLike) {
            throw new DefinitionBuilderException('Node does not match expected type of ClassLike');
        }

        $this->definition = BuildFromClassLikeNode::make($node, $this->buildResolver());
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

    /**
     * @return Class_
     */
    protected function buildClassNode(): Class_
    {
        $builder = $this->newNodeBuilder()
                        ->class($this->reflection->getShortName())
                        ->implement(...$this->reflection->getInterfaceNames());

        if ($this->reflection->getParentClass()) {
            $builder->extend($this->reflection->getParentClass()->getShortName());
        }

        return $builder;
    }

    /**
     * @return Enum_
     */
    protected function buildEnumNode(): Enum_
    {
        return $this->newNodeBuilder()
                    ->enum($this->reflection->getShortName())
                    ->implement(...$this->reflection->getInterfaceNames());
    }

    /**
     * @return Interface_
     */
    protected function buildInterfaceNode(): Interface_
    {
        return $this->newNodeBuilder()
                    ->interface($this->reflection->getShortName())
                    ->extend(...$this->reflection->getInterfaceNames());
    }

    /**
     * @return Namespace_|null
     * @throws DefinitionBuilderException
     */
    protected function buildNamespaceNode(): ?Namespace_
    {
        if ($this->isNamespaced()) {
            return $this->newNodeBuilder()
                        ->namespace($this->reflection->getNamespaceName())
                        ->addStmt($this->buildNode()->getNode());
        }

        return null;
    }

    /**
     * @return Builder
     * @throws DefinitionBuilderException
     */
    protected function buildNode(): Builder
    {
        return match ($this->type) {
            Type::TYPE_INTERFACE => $this->buildInterfaceNode(),
            Type::TYPE_CLASS, Type::TYPE_ABSTRACT => $this->buildClassNode(),
            Type::TYPE_TRAIT => $this->buildTraitNode(),
            Type::TYPE_ENUM => $this->buildEnumNode(),
            default => throw new DefinitionBuilderException('Could not build node'),
        };
    }

    /**
     * @return Resolver
     * @throws DefinitionBuilderException
     */
    protected function buildResolver(): Resolver
    {
        $resolver = ResolverFactory::create();

        if ($this->isNamespaced()) {
            $resolver->setNamespace($this->buildNamespaceNode()->getNode());
        }

        return $resolver;
    }

    /**
     * @return Trait_
     */
    protected function buildTraitNode(): Trait_
    {
        return $this->newNodeBuilder()
                    ->trait($this->reflection->getShortName());
    }

    /**
     * @return bool
     */
    protected function isNamespaced(): bool
    {
        return !empty($this->reflection->getNamespaceName());
    }

    /**
     * @return BuilderFactory
     */
    protected function newNodeBuilder(): BuilderFactory
    {
        return new BuilderFactory();
    }
}
