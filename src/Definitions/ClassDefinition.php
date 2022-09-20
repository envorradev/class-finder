<?php

namespace Envorra\ClassFinder\Definitions;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\TraitUse;
use Envorra\ClassFinder\Definitions\Traits\HasTraits;
use Envorra\ClassFinder\Definitions\Traits\HasParent;
use Envorra\ClassFinder\Definitions\Traits\HasInterfaces;
use Envorra\ClassFinder\Contracts\Definitions\ClassTypeDefinition;


/**
 * ClassDefinition
 *
 * @package Envorra\ClassFinder\Definitions
 *
 * @extends Definition<Class_>
 * @implements ClassTypeDefinition<self, InterfaceDefinition, TraitDefinition>
 */
class ClassDefinition extends Definition implements ClassTypeDefinition
{
    use HasParent;
    use HasInterfaces;
    use HasTraits;

    /**
     * @inheritDoc
     */
    protected function initInterfaces(): void
    {
        $this->addInterfaces($this->node->implements);
    }

    /**
     * @inheritDoc
     */
    protected function initParent(): void
    {
        $this->setParent($this->node->extends);
    }

    /**
     * @inheritDoc
     */
    protected function initTraits(): void
    {
        /** @var TraitUse $useTrait */
        foreach ($this->nodeFinder->findInstanceOf($this->node->stmts, TraitUse::class) as $useTrait) {
            $this->addTraits($useTrait->traits);
        }
    }


}
