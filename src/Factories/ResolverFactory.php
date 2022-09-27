<?php

namespace Envorra\ClassFinder\Factories;

use Envorra\ClassFinder\Contracts\Factory;
use Envorra\ClassFinder\Contracts\Resolver;
use Envorra\ClassFinder\Resolvers\ClassResolver;

/**
 * ResolverFactory
 *
 * @package Envorra\ClassFinder\Factories
 *
 * @implements Factory<Resolver>
 */
class ResolverFactory implements Factory
{
    /**
     * @inheritDoc
     */
    public static function create(mixed $from = null): Resolver
    {
        return new ClassResolver();
    }

    /**
     * @inheritDoc
     */
    public static function createOrFail(mixed $from = null): Resolver
    {
        return static::create();
    }

}
