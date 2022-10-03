<?php

namespace Envorra\ClassFinder\Definitions;

use PhpParser\Node;
use ReflectionClass;
use ReflectionException;
use PhpParser\NodeFinder;
use Envorra\ClassFinder\Enums\Type;
use Envorra\ClassFinder\Contracts\Resolver;
use Envorra\ClassFinder\Helpers\NodeHelper;
use Envorra\ClassFinder\Factories\TypeFactory;
use Envorra\ClassFinder\Factories\ResolverFactory;
use Envorra\ClassFinder\Contracts\Definitions\TypeDefinition;
use Envorra\ClassFinder\Contracts\Definitions\Attributes\CanHaveParent;
use Envorra\ClassFinder\Contracts\Definitions\Attributes\CanHaveTraits;
use Envorra\ClassFinder\Contracts\Definitions\Attributes\CanHaveInterfaces;

/**
 * Definition
 *
 * @package  Envorra\ClassFinder\Definitions
 *
 * @template TNode of Node
 *
 * @implements TypeDefinition<TNode>
 */
class Definition implements TypeDefinition
{
    public ?string $name = null;
    protected NodeFinder $nodeFinder;
    protected NodeHelper $nodeHelper;

    /**
     * @param  TNode          $node
     * @param  Resolver|null  $resolver
     * @param  Type|null      $type
     */
    public function __construct(
        protected Node $node,
        public ?Resolver $resolver = null,
        public ?Type $type = null
    ) {
        $this->preInit();
        $this->applyDefaultValues();
        $this->definitionInitializer();
        $this->runInitializers();
        $this->postInit();
    }

    /**
     * @inheritDoc
     */
    public function getFullyQualifiedName(): ?string
    {
        return $this->resolver->qualify($this->getName());
    }

    /**
     * @inheritDoc
     */
    public function getName(): ?string
    {
        return $this->name ?? $this->resolver->getName();
    }

    /**
     * @inheritDoc
     */
    public function getNamespace(): ?string
    {
        return $this->resolver->getNamespace();
    }

    /**
     * @return TNode
     */
    public function getNode(): Node
    {
        return $this->node;
    }

    /**
     * @inheritDoc
     */
    public function getRelatives(): array
    {
        $relatives = [];

        if ($this instanceof CanHaveInterfaces) {
            $relatives = array_merge($relatives, $this->getInterfaces());
        }

        if ($this instanceof CanHaveParent && $this->hasParent()) {
            $relatives[] = $this->getParent();
        }

        if ($this instanceof CanHaveTraits) {
            $relatives = array_merge($relatives, $this->getTraits());
        }

        return $relatives;
    }

    /**
     * @inheritDoc
     */
    public function getType(): Type
    {
        return $this->type;
    }

    /**
     * @return void
     */
    protected function applyDefaultValues(): void
    {
        $this->resolver ??= ResolverFactory::create();
        $this->type ??= TypeFactory::create($this->node);
        $this->nodeHelper = NodeHelper::make($this->node);
        $this->nodeFinder = new NodeFinder();
    }

    /**
     * @return void
     */
    protected function definitionInitializer(): void
    {
        $this->name = $this->nodeHelper->getName();
    }

    /**
     * @return void
     */
    protected function postInit(): void
    {

    }

    /**
     * @return void
     */
    protected function preInit(): void
    {

    }

    /**
     * @return void
     */
    protected function runInitializers(): void
    {
        try {
            foreach ((new ReflectionClass($this))->getMethods() as $reflectionMethod) {
                $method = $reflectionMethod->getName();
                if (str_starts_with($method, 'init') && $reflectionMethod->getNumberOfRequiredParameters() === 0) {
                    $this->$method();
                }
            }
        } catch (ReflectionException) {
            // ignore errors and continue.
        }
    }


}
