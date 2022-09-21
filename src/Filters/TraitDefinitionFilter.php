<?php declare(strict_types=1);

namespace Envorra\ClassFinder\Filters;

use Envorra\ClassFinder\Contracts\Definitions\TraitTypeDefinition;

/**
 * TraitDefinitionFilter
 *
 * @package Envorra\ClassFinder\Filters
 */
class TraitDefinitionFilter extends AbstractDefinitionFilter
{
    /**
     * @inheritDoc
     */
    protected function handle(mixed $incoming): bool
    {
        return $incoming instanceof TraitTypeDefinition;
    }

}
