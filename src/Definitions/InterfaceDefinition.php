<?php

namespace Envorra\ClassFinder\Definitions;

use PhpParser\Node\Stmt\Interface_;
use Envorra\ClassFinder\Definitions\Traits\HasInterfaces;
use Envorra\ClassFinder\Contracts\Definitions\InterfaceTypeDefinition;

/**
 * InterfaceDefinition
 *
 * @package Envorra\ClassFinder\Definitions
 *
 * @extends Definition<Interface_>
 * @implements InterfaceTypeDefinition<self>
 */
class InterfaceDefinition extends Definition implements InterfaceTypeDefinition
{
    use HasInterfaces;

    /**
     * @inheritDoc
     */
    protected function initInterfaces(): void
    {
        $this->addInterfaces($this->node->extends);
    }
}
