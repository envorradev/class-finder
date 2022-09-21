<?php declare(strict_types=1);

namespace Envorra\ClassFinder\Filters;

use Envorra\ClassFinder\Contracts\Definitions\InterfaceTypeDefinition;

/**
 * InterfaceDefinitionFilter
 *
 * @package Envorra\ClassFinder\Filters
 */
class InterfaceDefinitionFilter extends AbstractDefinitionFilter
{
    /**
     * @inheritDoc
     */
    protected function handle(mixed $incoming): bool
    {
        return $incoming instanceof InterfaceTypeDefinition;
    }

}
