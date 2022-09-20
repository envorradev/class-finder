<?php

namespace Envorra\ClassFinder\Helpers;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use ReflectionException;

/**
 * ReflectionHelper
 *
 * @package  Envorra\ClassFinder\Helpers
 *
 * @template T
 */
class ReflectionHelper
{
    public const MODIFIER_PUBLIC = 1;
    public const MODIFIER_PROTECTED = 2;
    public const MODIFIER_PRIVATE = 4;
    public const MODIFIER_DEFAULT = 8;
    public const MODIFIER_STATIC = 16;
    public const MODIFIER_READONLY = 32;
    public const MODIFIER_PROMOTED = 64;
    public const MODIFIER_ANY = 127;

    private const T_PROPERTY = 1;
    private const T_METHOD = 2;

    /**
     * @var ReflectionClass
     */
    protected ReflectionClass $reflection;

    /**
     * @param  T  $reflected
     * @throws ReflectionException
     */
    public function __construct(protected mixed $reflected)
    {
        $this->reflection = new ReflectionClass($this->reflected);
    }

    /**
     * @param  string  $name
     * @param  array   $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments): mixed
    {
        return $this->reflection->$name(...$arguments);
    }

    /**
     * @return T
     */
    public function getReflected(): mixed
    {
        return $this->reflected;
    }

    /**
     * @return ReflectionClass
     */
    public function getReflection(): ReflectionClass
    {
        return $this->reflection;
    }

    /**
     * @param  string  $method
     * @param  int     $modifier
     * @return bool
     */
    public function hasMethod(string $method, int $modifier = self::MODIFIER_ANY): bool
    {
        return $this->reflectionHas(self::T_METHOD, $method, $modifier);
    }

    /**
     * @param  string  $property
     * @param  int     $modifier
     * @return bool
     */
    public function hasProperty(string $property, int $modifier = self::MODIFIER_ANY): bool
    {
        return $this->reflectionHas(self::T_PROPERTY, $property, $modifier);
    }

    /**
     * @param  int     $type
     * @param  string  $name
     * @param  int     $modifier
     * @return bool
     */
    private function reflectionHas(int $type, string $name, int $modifier): bool
    {
        $method = match ($type) {
            self::T_PROPERTY => 'Property',
            self::T_METHOD => 'Method',
            default => null,
        };

        if (!$method) {
            return false;
        }

        $hasMethodName = 'has'.$method;
        $getMethodName = 'get'.$method;

        $reflectionHas = $this->reflection->$hasMethodName($name);

        if ($modifier === self::MODIFIER_ANY || !$reflectionHas) {
            return $reflectionHas;
        }

        try {
            $reflectionGet = $this->reflection->$getMethodName($name);
        } catch (ReflectionException) {
            return false;
        }

        return $this->validateModifier($reflectionGet, $modifier);
    }

    /**
     * @param  ReflectionProperty|ReflectionMethod  $reflection
     * @param  int                                  $modifier
     * @return bool
     */
    private function validateModifier(ReflectionProperty|ReflectionMethod $reflection, int $modifier): bool
    {
        $hasModifier = ($modifier & self::MODIFIER_ANY) > 0;

        if ($modifier & self::MODIFIER_PUBLIC) {
            $hasModifier = $hasModifier && $reflection->isPublic();
        }

        if ($modifier & self::MODIFIER_PROTECTED) {
            $hasModifier = $hasModifier && $reflection->isProtected();
        }

        if ($modifier & self::MODIFIER_PRIVATE) {
            $hasModifier = $hasModifier && $reflection->isPrivate();
        }

        if ($modifier & self::MODIFIER_DEFAULT) {
            $hasModifier = $hasModifier && $reflection->isDefault();
        }

        if ($modifier & self::MODIFIER_STATIC) {
            $hasModifier = $hasModifier && $reflection->isStatic();
        }

        if ($modifier & self::MODIFIER_READONLY) {
            $hasModifier = $hasModifier && $reflection->isReadOnly();
        }

        if ($modifier & self::MODIFIER_PROMOTED) {
            $hasModifier = $hasModifier && $reflection->isPromoted();
        }

        return $hasModifier;
    }
}
