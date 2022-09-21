<?php declare(strict_types=1);

namespace Envorra\ClassFinder\Filters;

use SplFileInfo;
use Envorra\ClassFinder\Contracts\FileFilter;

/**
 * AbstractFileFilter
 *
 * @package Envorra\ClassFinder\Filters
 *
 * @extends AbstractFilter<SplFileInfo>
 */
abstract class AbstractFileFilter extends AbstractFilter implements FileFilter
{
    /**
     * @inheritDoc
     */
    public function shouldFilter(mixed $item): bool
    {
        return $item instanceof SplFileInfo;
    }
}
