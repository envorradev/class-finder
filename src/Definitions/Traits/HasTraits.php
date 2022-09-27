<?php

namespace Envorra\ClassFinder\Definitions\Traits;

use PhpParser\Node\Name;
use Envorra\ClassFinder\Factories\DefinitionFactory;
use Envorra\ClassFinder\Contracts\Definitions\TraitTypeDefinition;

/**
 * UsesTraits
 *
 * @package  Envorra\ClassFinder\Definitions\Traits
 *
 * @template TTrait of TraitTypeDefinition
 */
trait HasTraits
{
    /**
     * @var TTrait[]
     */
    public array $traits = [];

    /**
     * @param  TTrait|Name|class-string|null  $trait
     * @return $this
     */
    public function addTrait(TraitTypeDefinition|Name|string|null $trait): static
    {
        $trait = DefinitionFactory::create($this->resolver->resolve($trait));

        if ($trait instanceof TraitTypeDefinition) {
            $this->traits[] = $trait;
        }

        return $this;
    }

    /**
     * @param  TTrait[]|Name[]|class-string[]  $traits
     * @return $this
     */
    public function addTraits(array $traits): static
    {
        foreach ($traits as $trait) {
            $this->addTrait($trait);
        }
        return $this;
    }

    /**
     * @return TTrait[]
     */
    public function getTraits(): array
    {
        return $this->traits;
    }

    /**
     * @return void
     */
    abstract protected function initTraits(): void;
}
