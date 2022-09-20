<?php

namespace Envorra\ClassFinder\Factories;

use Envorra\ClassFinder\Contracts\Resolver;
use Envorra\ClassFinder\Resolvers\ClassResolver;

/**
 * ResolverFactory
 *
 * @package Envorra\ClassFinder\Factories
 */
class ResolverFactory
{
    /**
     * @return Resolver
     */
    public static function create(): Resolver
    {
        return new ClassResolver();
    }
}
