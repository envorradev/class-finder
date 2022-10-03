<?php declare(strict_types=1);

namespace Envorra\ClassFinder\Builders;

use Closure;
use SplFileInfo;
use Envorra\ClassFinder\Finder;
use Envorra\ClassFinder\Contracts\Filter;
use Envorra\ClassFinder\Contracts\FileFilter;
use Envorra\ClassFinder\Contracts\DefinitionFilter;

/**
 * FinderBuilder
 *
 * @package Envorra\ClassFinder\Builders
 */
class FinderBuilder
{
    protected bool $defer = false;
    /** @var DefinitionFilter[]|Closure[] */
    protected array $definitionFilters = [];
    /** @var SplFileInfo[] */
    protected array $directories = [];
    /** @var FileFilter[]|Closure[] */
    protected array $fileFilters = [];
    protected bool $recursive = true;

    /**
     * @return Finder
     */
    public function build(): Finder
    {
        return new Finder(
            directories: $this->directories,
            fileFilters: $this->fileFilters,
            definitionFilters: $this->definitionFilters,
            recursive: $this->recursive,
            deferred: $this->defer,
        );
    }

    /**
     * @return $this
     */
    public function defer(): static
    {
        $this->defer = true;
        return $this;
    }

    /**
     * @param  SplFileInfo[]|string[]  $directories
     * @return static
     */
    public function directories(array $directories): static
    {
        foreach ($directories as $directory) {
            $this->directory($directory);
        }

        return $this;
    }

    /**
     * @param  SplFileInfo|string  $directory
     * @return static
     */
    public function directory(SplFileInfo|string $directory): static
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
     * @return $this
     */
    public function dontDefer(): static
    {
        $this->defer = false;
        return $this;
    }

    /**
     * @param  Filter  $filter
     * @return static
     */
    public function filter(Filter $filter): static
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
     * @param  DefinitionFilter|Closure  $filter
     * @return $this
     */
    public function filterDefinitionsUsing(DefinitionFilter|Closure $filter): static
    {
        $this->definitionFilters[] = $filter;
        return $this;
    }

    /**
     * @param  FileFilter|Closure  $filter
     * @return $this
     */
    public function filterFilesUsing(FileFilter|Closure $filter): static
    {
        $this->fileFilters[] = $filter;
        return $this;
    }

    /**
     * @return $this
     */
    public function notRecursive(): static
    {
        $this->recursive = false;
        return $this;
    }

    /**
     * @return $this
     */
    public function recursive(): static
    {
        $this->recursive = true;
        return $this;
    }
}
