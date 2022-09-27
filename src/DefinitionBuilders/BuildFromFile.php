<?php declare(strict_types=1);

namespace Envorra\ClassFinder\DefinitionBuilders;

use PhpParser\NodeVisitor;
use PhpParser\NodeTraverser;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Namespace_;
use Envorra\ClassFinder\CodeParser;
use Envorra\ClassFinder\Contracts\Visitor;
use Envorra\ClassFinder\Contracts\Resolver;
use Envorra\ClassFinder\Factories\ResolverFactory;
use Envorra\ClassFinder\Contracts\DefinitionBuilder;
use SplFileInfo;
use Envorra\ClassFinder\Factories\DefinitionFactory;
use Envorra\ClassFinder\Contracts\Definitions\TypeDefinition;
use PhpParser\Node;
use Envorra\ClassFinder\Exceptions\DefinitionBuilderException;

/**
 * BuildFromFile
 *
 * @package Envorra\ClassFinder\DefinitionBuilders
 */
final class BuildFromFile implements DefinitionBuilder
{
    /**
     * @var TypeDefinition
     */
    protected TypeDefinition $definition;

    /**
     * @var Node[]
     */
    protected array $nodes = [];

    protected NodeTraverser $nodeTraverser;

    protected Visitor $visitor;

    /**
     * @param  SplFileInfo  $file
     */
    public function __construct(public readonly SplFileInfo $file)
    {
        $this->visitor = new class extends NodeVisitorAbstract implements Visitor {
            public ClassLike $classLikeNode;

            public Resolver $resolver;

            public function __construct()
            {
                $this->resolver = ResolverFactory::create();
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
                    $this->classLikeNode = $node;
                    return NodeTraverser::DONT_TRAVERSE_CHILDREN;
                }

                return null;
            }
        };

        $this->nodeTraverser = new NodeTraverser();
        $this->nodeTraverser->addVisitor($this->visitor);
    }

    /**
     * @return $this
     */
    protected function traverse(): self
    {
        $this->nodes = $this->nodeTraverser->traverse(CodeParser::parseFile($this->file));
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function build(): self
    {
        $this->traverse();
        $this->definition = BuildFromClassLikeNode::make($this->visitor->classLikeNode, $this->visitor->resolver);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDefinition(): ?TypeDefinition
    {
        if(!isset($this->definition)) {
            try {
                $this->build();
            } catch (DefinitionBuilderException) {
                return null;
            }
        }
        return $this->definition;
    }

    /**
     * @param  SplFileInfo  $file
     * @return static
     */
    public static function instance(SplFileInfo $file): self
    {
        return new self($file);
    }

    /**
     * @param  SplFileInfo  $file
     * @return TypeDefinition|null
     */
    public static function make(SplFileInfo $file): ?TypeDefinition
    {
        return self::instance($file)->getDefinition();
    }
}
