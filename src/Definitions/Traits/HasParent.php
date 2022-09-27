<?php

namespace Envorra\ClassFinder\Definitions\Traits;

use PhpParser\Node\Name;
use Envorra\ClassFinder\Factories\DefinitionFactory;
use Envorra\ClassFinder\Contracts\Definitions\ClassTypeDefinition;

/**
 * ExtendsClasses
 *
 * @package  Envorra\ClassFinder\Definitions\Traits
 *
 * @template TClass of ClassTypeDefinition
 */
trait HasParent
{
    /**
     * @var TClass|null
     */
    public ?ClassTypeDefinition $parent = null;

    /**
     * @return TClass|null
     */
    public function getParent(): ?ClassTypeDefinition
    {
        return $this->parent;
    }

    /**
     * @param  TClass|Name|class-string|null  $parent
     * @return $this
     */
    public function setParent(ClassTypeDefinition|Name|string|null $parent): static
    {
        $parent = DefinitionFactory::create($this->resolver->resolve($parent));

        if ($parent instanceof ClassTypeDefinition) {
            $this->parent = $parent;
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function hasParent(): bool
    {
        return $this->getParent() !== null;
    }

    /**
     * @return void
     */
    abstract protected function initParent(): void;
}
