<?php

namespace Envorra\ClassFinder\Tests\TestEnvironment\TestClasses\ClassesWithTraits;

use Envorra\ClassFinder\Tests\TestEnvironment\TestTraits\TraitAB;

/**
 * ClassThatUsesAB
 *
 * @package Envorra\ClassFinder\Tests\TestEnvironment\TestClasses\ClassesWithTraits
 */
class ClassThatUsesAB
{
    use TraitAB;
}
