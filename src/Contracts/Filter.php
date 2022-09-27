<?php declare(strict_types=1);

namespace Envorra\ClassFinder\Contracts;

/**
 * Filter
 *
 * @package  Envorra\ClassFinder\Contracts
 *
 * @template T
 */
interface Filter
{
    /**
     * @param  T  $incoming
     * @return bool
     */
    public function filter(mixed $incoming): bool;

    /**
     * @param  mixed  $item
     * @return bool
     */
    public function shouldFilter(mixed $item): bool;
}
