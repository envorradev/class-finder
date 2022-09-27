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
    public static function throwFailure()
    {
        throw new self('Could not create a definition class');
    }
}
