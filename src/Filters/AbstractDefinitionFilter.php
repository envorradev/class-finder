<?php declare(strict_types=1);

namespace Envorra\ClassFinder\Filters;

use Envorra\ClassFinder\Contracts\DefinitionFilter;
use Envorra\ClassFinder\Contracts\Definitions\TypeDefinition;

/**
 * AbstractDefinitionFilter
 *
 * @package Envorra\ClassFinder\Filters
 *
 * @extends AbstractFilter<TypeDefinition>
 */
abstract class AbstractDefinitionFilter extends AbstractFilter implements DefinitionFilter
{
    /**
     * @inheritDoc
     */
    public function shouldFilter(mixed $item): bool
    {
        return $item instanceof TypeDefinition;
    }
}
