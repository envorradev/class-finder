<?php

namespace Envorra\ClassFinder\Contracts\Definitions;

use PhpParser\Node;
use Envorra\ClassFinder\Contracts\Resolver;
use Envorra\ClassFinder\Contracts\ClassType;

/**
 * TypeDefinition
 *
 * @package  Envorra\ClassFinder\Contracts
 *
 * @template TNode of Node
 */
interface TypeDefinition
{
    /**
     * @param  TNode           $node
     * @param  Resolver|null   $resolver
     * @param  ClassType|null  $type
     */
    public function __construct(Node $node, ?Resolver $resolver = null, ?ClassType $type = null);

    /**
     * @return string|null
     */
    public function getFullyQualifiedName(): ?string;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @return string|null
     */
    public function getNamespace(): ?string;

    /**
     * @return ClassType
     */
    public function getType(): ClassType;
}
