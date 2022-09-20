<?php

namespace Envorra\ClassFinder\Contracts\Definitions\Attributes;

use PhpParser\Node\Name;
use Envorra\ClassFinder\Contracts\Definitions\InterfaceTypeDefinition;

/**
 * CanImplement
 *
 * @package  Envorra\ClassFinder\Contracts
 *
 * @template TInterface of InterfaceTypeDefinition
 */
interface CanHaveInterfaces
{
    /**
     * @param  TInterface|Name|class-string|null  $interface
     * @return static
     */
    public function addInterface(InterfaceTypeDefinition|Name|string|null $interface): static;


    /**
     * @param  TInterface[]|Name[]|class-string[]  $interfaces
     * @return static
     */
    public function addInterfaces(array $interfaces): static;


    /**
     * @return TInterface[]
     */
    public function getInterfaces(): array;
}
