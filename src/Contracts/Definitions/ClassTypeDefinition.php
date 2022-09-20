<?php

namespace Envorra\ClassFinder\Contracts\Definitions;

use PhpParser\Node\Stmt\Class_;
use Envorra\ClassFinder\Contracts\Definitions\Attributes\CanHaveParent;
use Envorra\ClassFinder\Contracts\Definitions\Attributes\CanHaveTraits;
use Envorra\ClassFinder\Contracts\Definitions\Attributes\CanHaveInterfaces;

/**
 * ClassTypeDefinition
 *
 * @package  Envorra\ClassFinder\Contracts\Definitions
 *
 * @template TClass of self
 * @template TInterface of InterfaceTypeDefinition
 * @template TTrait of TraitTypeDefinition
 *
 * @extends TypeDefinition<Class_>
 * @extends CanHaveParent<TClass>
 * @extends CanHaveInterfaces<TInterface>
 * @extends CanHaveTraits<TTrait>
 */
interface ClassTypeDefinition extends TypeDefinition, CanHaveParent, CanHaveInterfaces, CanHaveTraits
{

}
