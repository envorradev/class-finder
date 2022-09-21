<?php

namespace Envorra\ClassFinder;

use SplFileInfo;
use DirectoryIterator;
use Envorra\ClassFinder\Contracts\Filter;
use Envorra\ClassFinder\Contracts\FileFilter;
use Envorra\ClassFinder\Contracts\DefinitionFilter;
use Envorra\ClassFinder\Factories\DefinitionFactory;
use Envorra\ClassFinder\Collections\DefinitionCollection;
use Envorra\ClassFinder\Contracts\Definitions\TypeDefinition;

/**
 * Finder
 *
 * @package Envorra\ClassFinder
 */
class Finder
{
    protected const DEFINITION_FILTER = 'definitionFilters';
    protected const FILE_FILTER = 'fileFilters';
    /** @var DefinitionFilter[] */
    protected array $definitionFilters = [];
    /** @var DefinitionCollection */
    protected DefinitionCollection $definitions;
    /** @var SplFileInfo[] */
    protected array $directories = [];
    /** @var FileFilter[] */
    protected array $fileFilters = [];
    /** @var bool */
    protected bool $recursive = true;
    /** @var SplFileInfo[] */
    protected array $traversed = [];

    public function __construct()
    {
        $this->definitions = new DefinitionCollection();
    }

    /**
     * @param  SplFileInfo[]|string[]  $directories
     * @return static
     */
    public function addDirectories(array $directories): static
    {
        foreach ($directories as $directory) {
            $this->addDirectory($directory);
        }

        return $this;
    }

    /**
     * @param  SplFileInfo|string  $directory
     * @return static
     */
    public function addDirectory(SplFileInfo|string $directory): static
    {
        if (is_string($directory)) {
            $directory = new SplFileInfo($directory);
        }

        if ($directory->isDir()) {
            $this->directories[] = $directory;
        }

        return $this;
    }

    /**
     * @param  Filter  $filter
     * @return static
     */
    public function addFilter(Filter $filter): static
    {
        if ($filter instanceof FileFilter) {
            $this->fileFilters[] = $filter;
        }

        if ($filter instanceof DefinitionFilter) {
            $this->definitionFilters[] = $filter;
        }

        return $this;
    }

    /**
     * @param  SplFileInfo  $file
     * @return TypeDefinition|null
     */
    public function definitionFromFile(SplFileInfo $file): ?TypeDefinition
    {
        if ($definition = DefinitionFactory::tryFromFile($file)) {
            return $this->filterDefinition($definition);
        }
        return null;
    }

    /**
     * @return static
     */
    public function find(): static
    {
        foreach ($this->directories as $directory) {
            if ($directory->getFilename() !== '..') {
                $this->traverseDirectory($directory);
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getDefinitionFilters(): array
    {
        return $this->definitionFilters;
    }

    /**
     * @return DefinitionCollection
     */
    public function getDefinitions(): DefinitionCollection
    {
        return $this->definitions;
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
     * @param  bool  $recursive
     * @return static
     */
    public function setRecursive(bool $recursive): static
    {
        $this->recursive = $recursive;
        return $this;
    }

    /**
     * @param  TypeDefinition|SplFileInfo  $item
     * @param  string                      $type
     * @return TypeDefinition|SplFileInfo|null
     */
    protected function filter(TypeDefinition|SplFileInfo $item, string $type): TypeDefinition|SplFileInfo|null
    {
        if ($type === self::DEFINITION_FILTER || $type === self::FILE_FILTER) {
            /** @var Filter $filter */
            foreach ($this->$type as $filter) {
                if (!$filter->filter($item)) {
                    return null;
                }
            }
        }
        return $item;
    }

    /**
     * @param  TypeDefinition  $definition
     * @return TypeDefinition|null
     */
    protected function filterDefinition(TypeDefinition $definition): ?TypeDefinition
    {
        return $this->filter($definition, self::DEFINITION_FILTER);
    }

    /**
     * @param  SplFileInfo  $file
     * @return SplFileInfo|null
     */
    protected function filterFile(SplFileInfo $file): ?SplFileInfo
    {
        return $this->filter($file, self::FILE_FILTER);
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

                if ($item->isFile() && $this->filterFile($item)) {
                    $this->definitions->push($this->definitionFromFile($item));
                }
            }
        }

        $this->traversed[] = $directory;
    }
}
