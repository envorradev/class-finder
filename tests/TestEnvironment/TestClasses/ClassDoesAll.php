<?php

namespace Envorra\ClassFinder\Tests\TestEnvironment\TestClasses;

use Envorra\ClassFinder\Tests\TestEnvironment\TestTraits\TraitA;
use Envorra\ClassFinder\Tests\TestEnvironment\TestContracts\InterfaceA;
use Envorra\ClassFinder\Tests\TestEnvironment\TestAbstractClasses\StandaloneAbstract;

/**
 * ClassDoesAll
 *
 * @package Envorra\ClassFinder\Tests\TestEnvironment\TestClasses
 */
class ClassDoesAll extends StandaloneAbstract implements InterfaceA
{
    use TraitA;
}
