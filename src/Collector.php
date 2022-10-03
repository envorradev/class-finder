<?php declare(strict_types=1);

namespace Envorra\ClassFinder;

use Ramsey\Uuid\Uuid;
use Envorra\ClassFinder\Contracts\Definitions\TypeDefinition;

/**
 * Collector
 *
 * @package Envorra\ClassFinder
 */
class Collector
{
    /** @var array<string, TypeDefinition> */
    protected array $definitions = [];

    /** @var array<string, class-string> */
    protected array $classes = [];

    /** @var array<class-string, string[]> */
    protected array $relativeMap = [];

    /**
     * @param  TypeDefinition|null  $definition
     * @return $this
     */
    public function collect(?TypeDefinition $definition): static
    {
        if(!is_null($definition)) {
            $uuid = $this->addDefinition($definition);
            $this->classes[$uuid] = $definition->getFullyQualifiedName();
            $this->addRelatives($definition, $uuid);
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
     * @param  TypeDefinition  $definition
     * @param  string|null     $uuid
     * @return void
     */
    protected function addRelatives(TypeDefinition $definition, ?string $uuid): void
    {
        $uuid ??= $this->getDefinitionUuid($definition) ?? $this->newUuid();

        foreach($definition->getRelatives() as $relative) {
            $this->addRelative($relative, $uuid);
        }
    }

    /**
     * @param  TypeDefinition  $relative
     * @param  string          $collectedUuid
     * @return void
     */
    protected function addRelative(TypeDefinition $relative, string $collectedUuid): void
    {
        $class = $relative->getFullyQualifiedName();

        if(!array_key_exists($class, $this->relativeMap)) {
            $this->relativeMap[$class] = [];
        }

        $this->relativeMap[$class][] = $collectedUuid;
    }

    /**
     * @param  TypeDefinition  $definition
     * @return string
     */
    protected function addDefinition(TypeDefinition $definition): string
    {
        $uuid = $this->getDefinitionUuid($definition) ?? $this->newUuid();
        $this->definitions[$uuid] = $definition;
        return $uuid;
    }

    /**
     * @param  TypeDefinition  $definition
     * @return string|null
     */
    public function getDefinitionUuid(TypeDefinition $definition): ?string
    {
        if(in_array($definition, $this->definitions)) {
            return array_search($definition, $this->definitions);
        }
        return null;
    }

    /**
     * @return string
     */
    protected function newUuid(): string
    {
        return Uuid::uuid4()->toString();
    }
}
