<?php

namespace Envorra\ClassFinder\Contracts\Definitions\Attributes;

use PhpParser\Node\Name;
use Envorra\ClassFinder\Contracts\Definitions\TraitTypeDefinition;

/**
 * CanHaveTraits
 *
 * @package  Envorra\ClassFinder\Contracts\Definitions\Attributes
 *
 * @template TTrait of TraitTypeDefinition
 */
interface CanHaveTraits
{
    /**
     * @param  TTrait|Name|class-string|null  $trait
     * @return static
     */
    public function addTrait(TraitTypeDefinition|Name|string|null $trait): static;


    /**
     * @param  TTrait[]|Name[]|class-string[]  $traits
     * @return static
     */
    public function addTraits(array $traits): static;


    /**
     * @return TTrait[]
     */
    public function getTraits(): array;
}
