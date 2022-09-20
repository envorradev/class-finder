<?php

namespace Envorra\ClassFinder\Helpers;

use PhpParser\Node;
use ReflectionException;
use PhpParser\Node\Name;
use PhpParser\Node\Identifier;

/**
 * NodeHelper
 *
 * @package  Envorra\ClassFinder\Helpers
 *
 * @template TNode of Node
 *
 * @extends ObjectHelper<TNode>
 */
class NodeHelper extends ObjectHelper
{
    /**
     * @param  TNode  $node
     * @return self|null
     */
    public static function make(Node $node): ?self
    {
        try {
            return new self($node);
        } catch (ReflectionException) {
            return null;
        }
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        /** @var self<Name|Identifier>|null $helper */
        $helper = $this->newFrom($this->getProperty('name'));

        if ($helper?->instanceMatchesOne([Name::class, Identifier::class])) {
            return $helper->getNode()->toString();
        }

        return null;
    }

    /**
     * @return TNode
     */
    public function getNode(): Node
    {
        return $this->reflected;
    }

    /**
     * @param  ?TNode  $node
     * @return self|null
     */
    public function newFrom(?Node $node): ?self
    {
        return $node ? self::make($node) : null;
    }
}
