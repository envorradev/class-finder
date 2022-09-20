<?php

namespace Envorra\ClassFinder\Tests\TestEnvironment\TestClasses\ClassesWithTraits;

use Envorra\ClassFinder\Tests\TestEnvironment\TestTraits\TraitA;

/**
 * ClassThatUsesA
 *
 * @package Envorra\ClassFinder\Tests\TestEnvironment\TestClasses\ClassesWithTraits
 */
trait ClassThatUsesA
{
    use TraitA;
}
