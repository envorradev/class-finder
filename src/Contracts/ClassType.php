<?php

namespace Envorra\ClassFinder\Contracts;

use PhpParser\Node;

/**
 * ClassType
 *
 * @package Envorra\ClassFinder\Contracts
 */
interface ClassType
{
    /**
     * @param  string  $name
     * @return self
     */
    public static function fromName(string $name): self;

    /**
     * @param  Node  $node
     * @return self
     */
    public static function fromNode(Node $node): self;

    /**
     * @param  int  $value
     * @return self
     */
    public static function fromValue(int $value): self;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getShortName(): string;

    /**
     * @return int
     */
    public function getValue(): int;
}
