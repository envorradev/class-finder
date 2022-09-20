<?php

namespace Envorra\ClassFinder\Definitions;

use PhpParser\Node\Stmt\Enum_;
use Envorra\ClassFinder\Definitions\Traits\HasInterfaces;
use Envorra\ClassFinder\Contracts\Definitions\EnumTypeDefinition;

/**
 * EnumDefinition
 *
 * @package Envorra\ClassFinder\Definitions
 *
 * @extends Definition<Enum_>
 * @implements EnumTypeDefinition<InterfaceDefinition>
 */
class EnumDefinition extends Definition implements EnumTypeDefinition
{
    use HasInterfaces;

    /**
     * @inheritDoc
     */
    protected function initInterfaces(): void
    {
        $this->addInterfaces($this->node->implements);
    }
}
