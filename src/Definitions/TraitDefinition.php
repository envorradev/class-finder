<?php

namespace Envorra\ClassFinder\Definitions;

use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Stmt\TraitUse;
use Envorra\ClassFinder\Definitions\Traits\HasTraits;
use Envorra\ClassFinder\Contracts\Definitions\TraitTypeDefinition;

/**
 * TraitDefinition
 *
 * @package Envorra\ClassFinder\Definitions
 *
 * @extends Definition<Trait_>
 * @implements TraitTypeDefinition<self>
 */
class TraitDefinition extends Definition implements TraitTypeDefinition
{
    use HasTraits;

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
