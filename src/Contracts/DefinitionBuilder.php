<?php declare(strict_types=1);

namespace Envorra\ClassFinder\Contracts;

use Envorra\ClassFinder\Contracts\Definitions\TypeDefinition;
use Envorra\ClassFinder\Exceptions\DefinitionBuilderException;

/**
 * Builder
 *
 * @package Envorra\ClassFinder\Contracts
 */
interface DefinitionBuilder
{
    /**
     * @return TypeDefinition|null
     */
    public function getDefinition(): ?TypeDefinition;

    /**
     * @return $this
     * @throws DefinitionBuilderException
     */
    public function build(): self;
}
