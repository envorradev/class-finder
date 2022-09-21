<?php declare(strict_types=1);

namespace Envorra\ClassFinder\Filters;

use Envorra\ClassFinder\Enums\Type;
use Envorra\ClassFinder\Contracts\Definitions\ClassTypeDefinition;

/**
 * ClassDefinitionFilter
 *
 * @package Envorra\ClassFinder\Filters
 */
class ClassDefinitionFilter extends AbstractDefinitionFilter
{
    /**
     * @inheritDoc
     */
    protected function handle(mixed $incoming): bool
    {
        return $incoming instanceof ClassTypeDefinition && $incoming->getType() === Type::TYPE_CLASS;
    }
}
