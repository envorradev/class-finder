<?php

namespace Envorra\ClassFinder\Definitions\Traits;

use PhpParser\Node\Name;
use Envorra\ClassFinder\Factories\DefinitionFactory;
use Envorra\ClassFinder\Contracts\Definitions\InterfaceTypeDefinition;

/**
 * ImplementsInterfaces
 *
 * @package  Envorra\ClassFinder\Definitions\Traits
 *
 * @template TInterface of InterfaceTypeDefinition
 */
trait HasInterfaces
{
    /**
     * @var TInterface[]
     */
    protected array $interfaces = [];

    /**
     * @param  TInterface|Name|class-string|null  $interface
     * @return $this
     */
    public function addInterface(InterfaceTypeDefinition|Name|string|null $interface): static
    {
        $interface = DefinitionFactory::tryMatchDefinitionClass(
            from: $this->resolver->resolve($interface),
            definitionClass: InterfaceTypeDefinition::class
        );

        if ($interface) {
            $this->interfaces[] = $interface;
        }

        return $this;
    }

    /**
     * @param  TInterface[]|Name[]|class-string[]  $interfaces
     * @return $this
     */
    public function addInterfaces(array $interfaces): static
    {
        foreach ($interfaces as $interface) {
            $this->addInterface($interface);
        }
        return $this;
    }

    /**
     * @return TInterface[]
     */
    public function getInterfaces(): array
    {
        return $this->interfaces;
    }

    /**
     * @return void
     */
    abstract protected function initInterfaces(): void;
}
