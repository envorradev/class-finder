<?php

namespace Envorra\ClassFinder;

use Closure;
use SplFileInfo;
use DirectoryIterator;
use Envorra\ClassFinder\Enums\Type;
use Envorra\ClassFinder\Contracts\Filter;
use Envorra\ClassFinder\Contracts\FileFilter;
use Envorra\ClassFinder\Contracts\DefinitionFilter;
use Envorra\ClassFinder\Factories\DefinitionFactory;
use Envorra\ClassFinder\Contracts\Definitions\TypeDefinition;

/**
 * Finder
 *
 * @package Envorra\ClassFinder
 */
class Finder
{
    protected Collector $collector;
    /** @var SplFileInfo[] */
    protected array $traversed = [];

    /**
     * @param  SplFileInfo[]                 $directories
     * @param  FileFilter[]|Closure[]        $fileFilters
     * @param  DefinitionFilter[]|Closure[]  $definitionFilters
     * @param  bool                          $recursive
     * @param  bool                          $deferred
     */
    public function __construct(
        public readonly array $directories = [],
        public readonly array $fileFilters = [],
        public readonly array $definitionFilters = [],
        public readonly bool $recursive = true,
        public readonly bool $deferred = false,
    ) {
        $this->collector = new Collector();

        if (!$this->deferred) {
            $this->scan();
        }
    }

    /**
     * @param  SplFileInfo  $file
     * @return TypeDefinition|null
     */
    public function definitionFromFile(SplFileInfo $file): ?TypeDefinition
    {
        if ($definition = DefinitionFactory::create($file)) {
            return $this->filter($definition);
        }
        return null;
    }

    /**
     * @return array
     */
    public function getClassNames(): array
    {
        return $this->collector->getClasses();
    }

    /**
     * @return array
     */
    public function getClasses(): array
    {
        return $this->collector->getDefinitions();
    }

    /**
     * @return Collector
     */
    public function getCollector(): Collector
    {
        return $this->collector;
    }

    /**
     * @return array
     */
    public function getDefinitionFilters(): array
    {
        return $this->definitionFilters;
    }

    /**
     * @return SplFileInfo[]
     */
    public function getDirectories(): array
    {
        return $this->directories;
    }

    /**
     * @return array
     */
    public function getFileFilters(): array
    {
        return $this->fileFilters;
    }

    /**
     * @param  TypeDefinition|string  $class
     * @param  Type                   $type
     * @return class-string[]
     */
    public function getSubClassNames(TypeDefinition|string $class, Type $type = Type::TYPE_CLASS): array
    {
        return array_map(
            callback: fn(TypeDefinition $definition) => $definition->getFullyQualifiedName(),
            array: $this->getSubClasses($class, $type)
        );
    }

    /**
     * @param  TypeDefinition|string  $class
     * @param  Type                   $type
     * @return TypeDefinition[]
     */
    public function getSubClasses(TypeDefinition|string $class, Type $type = Type::TYPE_CLASS): array
    {
        return array_filter(
            array: $this->collector->getSubClasses($class, $type),
            callback: fn(TypeDefinition $definition) => $this->filter($definition)
        );
    }

    /**
     * @return array
     */
    public function getTraversed(): array
    {
        return $this->traversed;
    }

    /**
     * @return bool
     */
    public function isRecursive(): bool
    {
        return $this->recursive;
    }

    /**
     * @return void
     */
    public function scan(): void
    {
        foreach ($this->directories as $directory) {
            if ($directory->getFilename() !== '..') {
                $this->traverseDirectory($directory);
            }
        }
    }

    /**
     * @param  TypeDefinition|SplFileInfo  $item
     * @return TypeDefinition|SplFileInfo|null
     */
    protected function filter(TypeDefinition|SplFileInfo $item): TypeDefinition|SplFileInfo|null
    {
        $type = $item instanceof TypeDefinition ? 'definitionFilters' : 'fileFilters';

        /** @var Filter|Closure $filter */
        foreach ($this->$type as $filter) {
            if ($filter instanceof Filter) {
                if (!$filter->filter($item)) {
                    return null;
                }
            } else {
                if (!$filter($item)) {
                    return null;
                }
            }
        }
        return $item;
    }

    /**
     * @param  SplFileInfo  $directory
     * @return void
     */
    protected function traverseDirectory(SplFileInfo $directory): void
    {
        /** @var DirectoryIterator $iterator */
        foreach (new DirectoryIterator($directory) as $iterator) {
            $item = $iterator->getFileInfo();

            if (!$iterator->isDot()) {
                if ($item->isDir() && $this->recursive) {
                    $this->traverseDirectory($item);
                }

                if ($item->isFile() && $this->filter($item)) {
                    $this->collector->collect($this->definitionFromFile($item));
                }
            }
        }

        $this->traversed[] = $directory;
    }
}
