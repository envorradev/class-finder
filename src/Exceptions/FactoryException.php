<?php declare(strict_types=1);

namespace Envorra\ClassFinder\Exceptions;

use Exception;

/**
 * FactoryException
 *
 * @package Envorra\ClassFinder\Exceptions
 */
class FactoryException extends Exception
{
    /**
     * @return mixed
     * @throws FactoryException
     */
    /**
     * @return mixed
     * @throws FactoryException
     */
    public static function throwFailure(): mixed
    {
        throw new self('Could not create a definition class');
    }
}
