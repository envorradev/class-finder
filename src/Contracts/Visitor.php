<?php declare(strict_types=1);

namespace Envorra\ClassFinder\Contracts;

use PhpParser\NodeVisitor;
use PhpParser\Node\Stmt\ClassLike;

/**
 * Visitor
 *
 * @package Envorra\ClassFinder\Contracts
 *
 * @property Resolver $resolver
 * @property ClassLike $classLikeNode
 */
interface Visitor extends NodeVisitor
{
//    /**
//     * @return Resolver
//     */
//    public function getResolver(): Resolver;
//
//    /**
//     * @return ClassLike
//     */
//    public function getClassLikeNode(): ClassLike;
}
