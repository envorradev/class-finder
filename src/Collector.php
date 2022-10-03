<?php declare(strict_types=1);

namespace Envorra\ClassFinder;

use Envorra\ClassFinder\Contracts\Definitions\TypeDefinition;

/**
 * Collector
 *
 * @package Envorra\ClassFinder
 */
class Collector
{
    /** @var class-string[] */
    protected array $classes = [];
    /** @var array<class-string, TypeDefinition> */
    protected array $definitions = [];
    /** @var array<class-string, class-string[]> */
    protected array $relativeMap = [];

    /**
     * @param  TypeDefinition|null  $definition
     * @return $this
     */
    public function collect(?TypeDefinition $definition): static
    {
        if (!is_null($definition)) {
            $this->addDefinition($definition);
            $this->addClass($definition->getFullyQualifiedName());
            $this->addToRelativeMap($definition);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getClasses(): array
    {
        return $this->classes;
    }

    /**
     * @return array
     */
    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    /**
     * @return array
     */
    public function getRelativeMap(): array
    {
        return $this->relativeMap;
    }

    /**
     * @param  TypeDefinition|string  $class
     * @return array
     */
    public function getSubClassNames(TypeDefinition|string $class): array
    {
        if (!is_string($class)) {
            $class = $class->getFullyQualifiedName();
        }

        return $this->relativeMap[$class] ?? [];
    }

    /**
     * @param  TypeDefinition|string  $class
     * @return array
     */
    public function getSubClasses(TypeDefinition|string $class): array
    {
        $definitions = [];

        foreach ($this->getSubClassNames($class) as $className) {
            if (array_key_exists($className, $this->definitions)) {
                $definitions[] = $this->definitions[$className];
            }
        }

        return $definitions;
    }

    /**
     * @param  string  $class
     * @return void
     */
    protected function addClass(string $class): void
    {
        if (!in_array($class, $this->classes)) {
            $this->classes[] = $class;
        }
    }

    /**
     * @param  TypeDefinition  $definition
     * @return void
     */
    protected function addDefinition(TypeDefinition $definition): void
    {
        $this->definitions[$definition->getFullyQualifiedName()] = $definition;
    }

    /**
     * @param  TypeDefinition  $definition
     * @param  array           $previous
     * @return void
     */
    protected function addToRelativeMap(TypeDefinition $definition, array $previous = []): void
    {
        $class = $definition->getFullyQualifiedName();
        if (!array_key_exists($class, $this->relativeMap)) {
            $this->relativeMap[$class] = [];
        }

        if (!empty($previous)) {
            $this->relativeMap[$class] = array_values(array_unique(array_merge($previous, $this->relativeMap[$class])));
        }

        foreach ($definition->getRelatives() as $relative) {
            $this->addToRelativeMap($relative, array_merge([$class], $previous));
        }
    }
}
