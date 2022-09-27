<?php declare(strict_types=1);

namespace Envorra\ClassFinder\DefinitionBuilders;

use SplFileInfo;
use ReflectionClass;
use ReflectionException;
use Envorra\ClassFinder\Contracts\DefinitionBuilder;
use Envorra\ClassFinder\Contracts\Definitions\TypeDefinition;
use Envorra\ClassFinder\Exceptions\DefinitionBuilderException;

/**
 * BuildFromString
 *
 * @package Envorra\ClassFinder\DefinitionBuilders
 */
final class BuildFromString implements DefinitionBuilder
{
    /**
     * @var TypeDefinition
     */
    protected TypeDefinition $definition;

    /**
     * @param  string  $string
     */
    public function __construct(public readonly string $string)
    {

    }


    /**
     * @inheritDoc
     */
    public function getDefinition(): ?TypeDefinition
    {
        if(!isset($this->definition)) {
            try {
                $this->build();
            } catch (DefinitionBuilderException) {
                return null;
            }
        }
        return $this->definition;
    }

    /**
     * @inheritDoc
     */
    public function build(): self
    {
        $file = new SplFileInfo($this->string);

        if($file->isFile()) {
            $this->definition = BuildFromFile::make($file);
        } else {
            try {
                $reflection = new ReflectionClass($this->string);

                if($reflection->isInternal()) {
                    $this->definition = BuildFromReflection::make($reflection);
                } else {
                    $this->definition = BuildFromFile::make(new SplFileInfo($reflection->getFileName()));
                }
            } catch (ReflectionException) {
                throw new DefinitionBuilderException('Given string cannot be converted to a valid definition');
            }
        }

        return $this;
    }

    /**
     * @param  string  $string
     * @return static
     */
    public static function instance(string $string): self
    {
        return new self($string);
    }

    /**
     * @param  string  $string
     * @return TypeDefinition|null
     */
    public static function make(string $string): ?TypeDefinition
    {
        return self::instance($string)->getDefinition();
    }
}
