<?php

namespace Envorra\ClassFinder\Contracts\Definitions;


use PhpParser\Node\Stmt\Enum_;
use Envorra\ClassFinder\Contracts\Definitions\Attributes\CanHaveInterfaces;

/**
 * EnumTypeDefinition
 *
 * @package  Envorra\ClassFinder\Contracts\Definitions
 *
 * @template TInterface of InterfaceTypeDefinition
 *
 * @extends TypeDefinition<Enum_>
 * @extends CanHaveInterfaces<TInterface>
 */
interface EnumTypeDefinition extends TypeDefinition, CanHaveInterfaces
{

}
