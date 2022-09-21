<?php

namespace Envorra\ClassFinder;

use SplFileInfo;
use PhpParser\Node;
use PhpParser\NodeVisitor;
use PhpParser\NodeTraverser;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Namespace_;
use Envorra\ClassFinder\Contracts\Resolver;
use Envorra\ClassFinder\Factories\ResolverFactory;
use Envorra\ClassFinder\Factories\DefinitionFactory;
use Envorra\ClassFinder\Contracts\Definitions\TypeDefinition;

/**
 * FileHandler
 *
 * @package Envorra\ClassFinder
 */
class FileHandler implements NodeVisitor
{
    /**
     * @var array
     */
    protected array $ast = [];
    /**
     * @var TypeDefinition|null
     */
    protected ?TypeDefinition $definition = null;
    /**
     * @var Node[]
     */
    protected array $nodes = [];
    /**
     * @var NodeTraverser
     */
    protected NodeTraverser $traverser;

    /**
     * @param  SplFileInfo    $file
     * @param  Resolver|null  $resolver
     */
    public function __construct(
        protected SplFileInfo $file,
        protected ?Resolver $resolver = null,
    ) {
        $this->resolver ??= ResolverFactory::create();
        $this->traverser = new NodeTraverser();
        $this->ast = CodeParser::parseFile($this->file);
        $this->initVisitors();
    }

    /**
     * @inheritDoc
     */
    public function afterTraverse(array $nodes): Node|int|null
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function beforeTraverse(array $nodes): Node|int|null
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function enterNode(Node $node): Node|int|null
    {
        if ($node instanceof Namespace_) {
            $this->resolver->setNamespace($node);
        }

        if ($node instanceof Use_ || $node instanceof GroupUse) {
            $this->resolver->addUse($node);
        }

        if ($node instanceof ClassLike) {
            $this->definition = DefinitionFactory::createFromClassLikeNode($node, $this->resolver);
            return NodeTraverser::DONT_TRAVERSE_CHILDREN;
        }

        return null;
    }

    /**
     * @return TypeDefinition|null
     */
    public function getDefinition(): ?TypeDefinition
    {
        return $this->definition;
    }

    /**
     * @return Node[]
     */
    public function getNodes(): array
    {
        if (empty($this->nodes)) {
            $this->traverse();
        }

        return $this->nodes;
    }

    /**
     * @return void
     */
    public function initVisitors(): void
    {
        $this->traverser->addVisitor($this);

        foreach ($this->visitors() as $visitor) {
            $this->traverser->addVisitor($visitor);
        }
    }

    /**
     * @inheritDoc
     */
    public function leaveNode(Node $node): Node|int|null
    {
        return null;
    }

    /**
     * @return void
     */
    public function traverse(): void
    {
        $this->nodes = $this->traverser->traverse($this->ast);
    }

    /**
     * @return NodeVisitor[]
     */
    public function visitors(): array
    {
        return [];
    }
}
