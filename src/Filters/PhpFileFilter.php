<?php declare(strict_types=1);

namespace Envorra\ClassFinder\Filters;

use SplFileInfo;

/**
 * PhpFileFilter
 *
 * @package Envorra\ClassFinder\Filters
 */
class PhpFileFilter extends AbstractFileFilter
{
    /**
     * @param  SplFileInfo  $incoming
     * @return bool
     */
    protected function handle(mixed $incoming): bool
    {
        return $incoming->isFile() && $incoming->getExtension() === 'php';
    }
}
