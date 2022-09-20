<?php

namespace Envorra\ClassFinder\Contracts\Definitions\Attributes;

use PhpParser\Node\Name;
use Envorra\ClassFinder\Contracts\Definitions\ClassTypeDefinition;

/**
 * CanExtend
 *
 * @package  Envorra\ClassFinder\Contracts\Definitions\Attributes
 *
 * @template TParent of ClassTypeDefinition
 */
interface CanHaveParent
{
    /**
     * @return TParent|null
     */
    public function getParent(): mixed;

    /**
     * @return bool
     */
    public function hasParent(): bool;

    /**
     * @param  TParent|Name|class-string|null  $parent
     * @return static
     */
    public function setParent(ClassTypeDefinition|Name|string|null $parent): static;
}
