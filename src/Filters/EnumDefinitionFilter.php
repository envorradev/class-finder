<?php declare(strict_types=1);

namespace Envorra\ClassFinder\Filters;

use Envorra\ClassFinder\Contracts\Definitions\EnumTypeDefinition;

/**
 * EnumDefinitionFilter
 *
 * @package Envorra\ClassFinder\Filters
 */
class EnumDefinitionFilter extends AbstractDefinitionFilter
{
    /**
     * @inheritDoc
     */
    protected function handle(mixed $incoming): bool
    {
        return $incoming instanceof EnumTypeDefinition;
    }

}
