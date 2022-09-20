<?php

namespace Envorra\ClassFinder\Contracts\Definitions;

use PhpParser\Node\Stmt\Trait_;
use Envorra\ClassFinder\Contracts\Definitions\Attributes\CanHaveTraits;

/**
 * TraitTypeDefinition
 *
 * @package  Envorra\ClassFinder\Contracts\Definitions
 *
 * @template TTrait of self
 *
 * @extends TypeDefinition<Trait_>
 * @extends CanHaveTraits<TTrait>
 */
interface TraitTypeDefinition extends TypeDefinition, CanHaveTraits
{

}
