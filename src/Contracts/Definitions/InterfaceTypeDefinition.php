<?php

namespace Envorra\ClassFinder\Contracts\Definitions;

use PhpParser\Node\Stmt\Interface_;
use Envorra\ClassFinder\Contracts\Definitions\Attributes\CanHaveInterfaces;

/**
 * InterfaceTypeDefinition
 *
 * @package  Envorra\ClassFinder\Contracts\Definitions
 *
 * @template TInterface of self
 *
 * @extends TypeDefinition<Interface_>
 * @extends CanHaveInterfaces<TInterface>
 */
interface InterfaceTypeDefinition extends TypeDefinition, CanHaveInterfaces
{

}
