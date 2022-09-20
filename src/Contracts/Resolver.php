<?php

namespace Envorra\ClassFinder\Contracts;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Namespace_;

/**
 * Resolver
 *
 * @package Envorra\ClassFinder\Contracts
 */
interface Resolver
{
    /**
     * @param  Use_|GroupUse  $useNode
     * @return $this
     */
    public function addUse(Use_|GroupUse $useNode): static;

    /**
     * @return string|null
     */
    public function getFullyQualifiedName(): ?string;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @return Name|Identifier|null
     */
    public function getNameNode(): Name|Identifier|null;

    /**
     * @return string|null
     */
    public function getNamespace(): ?string;

    /**
     * @return Namespace_|null
     */
    public function getNamespaceNode(): ?Namespace_;

    /**
     * @param  Name|Identifier|string|null  $name
     * @return string|null
     */
    public function qualify(Name|Identifier|string|null $name): ?string;

    /**
     * @param  Name|Identifier|string|null  $name
     * @return class-string|null
     */
    public function resolve(Name|Identifier|string|null $name): ?string;

    /**
     * @param  Name|Identifier  $nameNode
     * @return $this
     */
    public function setName(Name|Identifier $nameNode): static;

    /**
     * @param  Namespace_  $namespaceNode
     * @return $this
     */
    public function setNamespace(Namespace_ $namespaceNode): static;
}
