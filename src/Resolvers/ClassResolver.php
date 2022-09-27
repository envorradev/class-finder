<?php

namespace Envorra\ClassFinder\Resolvers;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Use_;
use InvalidArgumentException;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\UseUse;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Namespace_;
use Envorra\ClassFinder\Contracts\Resolver;

/**
 * ClassResolver
 *
 * @package Envorra\ClassFinder\Resolvers
 */
class ClassResolver implements Resolver
{
    /**
     * @var string|null
     */
    public ?string $name = null;
    /**
     * @var Name|Identifier|null
     */
    public Name|Identifier|null $nameNode = null;
    /**
     * @var string|null
     */
    public ?string $namespace = null;
    /**
     * @var string[]
     */
    public array $namespaceParts = [];
    /**
     * @var array<string, UseUse>
     */
    public array $useMap = [];
    /**
     * @var Namespace_|null
     */
    protected ?Namespace_ $namespaceNode = null;

    /**
     * @inheritDoc
     */
    public function addUse(Use_|GroupUse $useNode): static
    {
        foreach ($useNode->uses as $use) {
            $this->useMap[$use->getAlias()->toString()] = $use;
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFullyQualifiedName(): ?string
    {
        return $this->qualify($this->name);
    }

    /**
     * @inheritDoc
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function setName(Name|Identifier $nameNode): static
    {
        $this->nameNode = $nameNode;
        $this->name = $nameNode->toString();
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getNameNode(): Name|Identifier|null
    {
        return $this->nameNode;
    }

    /**
     * @inheritDoc
     */
    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * @inheritDoc
     */
    public function setNamespace(Namespace_ $namespaceNode): static
    {
        $this->namespaceNode = $namespaceNode;
        $this->namespaceParts = $namespaceNode->name->parts;
        $this->namespace = implode('\\', $this->namespaceParts);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getNamespaceNode(): ?Namespace_
    {
        return $this->namespaceNode;
    }

    /**
     * @return array<string, UseUse>
     */
    public function getUseMap(): array
    {
        return $this->useMap;
    }

    /**
     * @inheritDoc
     */
    public function qualify(Name|Identifier|string|null $name): ?string
    {
        if ($name && !is_string($name)) {
            $name = $name->toString();
        }

        try {
            return Name::concat($this->namespaceParts, $name)->toString();
        } catch (InvalidArgumentException) {
            return $this->namespace ?? $name;
        }
    }

    /**
     * @inheritDoc
     */
    public function resolve(Name|Identifier|string|null $name): ?string
    {
        if (!$name) {
            return null;
        }

        if (!is_string($name)) {
            $name = $name->toString();
        }

        return array_key_exists($name, $this->useMap) ? $this->useMap[$name]->name->toString() : $this->qualify($name);
    }


}
