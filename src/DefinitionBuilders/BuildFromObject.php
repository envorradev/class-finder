<?php declare(strict_types=1);

namespace Envorra\ClassFinder\DefinitionBuilders;

use SplFileInfo;
use ReflectionClass;
use Envorra\ClassFinder\Contracts\DefinitionBuilder;
use Envorra\ClassFinder\Contracts\Definitions\TypeDefinition;
use Envorra\ClassFinder\Exceptions\DefinitionBuilderException;

/**
 * BuildFromObject
 *
 * @package Envorra\ClassFinder\DefinitionBuilders
 */
final class BuildFromObject implements DefinitionBuilder
{
    protected TypeDefinition $definition;

    protected ReflectionClass $reflectionClass;

    /**
     * @param  object  $object
     */
    public function __construct(public readonly object $object)
    {
        $this->reflectionClass = new ReflectionClass($this->object);
    }

    /**
     * @param  object  $object
     * @return static
     */
    public static function instance(object $object): self
    {
        return new self($object);
    }

    /**
     * @param  object  $object
     * @return TypeDefinition|null
     */
    public static function make(object $object): ?TypeDefinition
    {
        return self::instance($object)->getDefinition();
    }

    /**
     * @inheritDoc
     */
    public function build(): self
    {
        if ($this->reflectionClass->isInternal()) {
            $this->definition = $this->buildFromReflection();
        } else {
            $this->definition = $this->buildFromFile();
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDefinition(): ?TypeDefinition
    {
        if (!isset($this->definition)) {
            try {
                $this->build();
            } catch (DefinitionBuilderException) {
                return null;
            }
        }
        return $this->definition;
    }

    /**
     * @return TypeDefinition
     */
    protected function buildFromFile(): TypeDefinition
    {
        return BuildFromFile::make($this->getFile());
    }

    /**
     * @return TypeDefinition
     */
    protected function buildFromReflection(): TypeDefinition
    {
        return BuildFromReflection::make($this->reflectionClass);
    }

    /**
     * @return SplFileInfo|null
     */
    protected function getFile(): ?SplFileInfo
    {
        if ($this->reflectionClass->isUserDefined()) {
            return new SplFileInfo($this->reflectionClass->getFileName());
        }
        return null;
    }
}
