<?php

namespace Envorra\ClassFinder\Contracts\Definitions;

use PhpParser\Node;
use Envorra\ClassFinder\Enums\Type;
use Envorra\ClassFinder\Contracts\Resolver;

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
     * @param  Type|null  $type
     */
    public function __construct(Node $node, ?Resolver $resolver = null, ?Type $type = null);

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
     * @return Type
     */
    public function getType(): Type;
}
