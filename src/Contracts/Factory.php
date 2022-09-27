<?php declare(strict_types=1);

namespace Envorra\ClassFinder\Contracts;

use Envorra\ClassFinder\Exceptions\FactoryException;

/**
 * Factory
 *
 * @package  Envorra\ClassFinder\Contracts
 *
 * @template T
 */
interface Factory
{
    /**
     * @param  mixed  $from
     * @return T|null
     */
    public static function create(mixed $from = null): mixed;

    /**
     * @param  mixed  $from
     * @return T
     * @throws FactoryException
     */
    public static function createOrFail(mixed $from = null): mixed;
}
