<?php declare(strict_types=1);

namespace Envorra\ClassFinder\Filters;

use Envorra\ClassFinder\Contracts\Filter;

/**
 * AbstractFilter
 *
 * @package  Envorra\ClassFinder\Filters
 *
 * @template T
 *
 * @implements Filter<T>
 */
abstract class AbstractFilter implements Filter
{
    /**
     * @inheritDoc
     */
    public function filter(mixed $incoming): bool
    {
        if ($this->shouldFilter($incoming)) {
            return $this->handle($incoming);
        }
        return true;
    }

    /**
     * @param  mixed  $incoming
     * @return bool
     */
    abstract protected function handle(mixed $incoming): bool;
}
