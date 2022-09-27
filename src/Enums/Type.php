<?php

namespace Envorra\ClassFinder\Enums;

use PhpParser\Node;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Stmt\Interface_;

/**
 * Type
 *
 * @package Envorra\ClassFinder\Enums
 */
enum Type: int
{
    case TYPE_UNKNOWN = 0;
    case TYPE_CLASS = 1;
    case TYPE_ABSTRACT = 2;
    case TYPE_INTERFACE = 4;
    case TYPE_ENUM = 8;
    case TYPE_TRAIT = 16;

    case TYPE_INSTANTIABLE = 9;       // 1|8
    case TYPE_NON_INSTANTIABLE = 22;  // 0|2|4|16
    case TYPE_ANY = 31;               // 0|1|2|4|8|16


    /**
     * @param  string  $name
     * @return static
     */
    public static function fromName(string $name): self
    {
        return match (strtoupper($name)) {
            'CLASS', 'TYPE_CLASS' => self::TYPE_CLASS,
            'ABSTRACT', 'TYPE_ABSTRACT' => self::TYPE_ABSTRACT,
            'INTERFACE', 'TYPE_INTERFACE' => self::TYPE_INTERFACE,
            'ENUM', 'TYPE_ENUM' => self::TYPE_ENUM,
            'TRAIT', 'TYPE_TRAIT' => self::TYPE_TRAIT,
            'INSTANTIABLE', 'TYPE_INSTANTIABLE' => self::TYPE_INSTANTIABLE,
            'NON_INSTANTIABLE', 'TYPE_NON_INSTANTIABLE' => self::TYPE_NON_INSTANTIABLE,
            'ANY', 'TYPE_ANY' => self::TYPE_ANY,
            default => self::TYPE_UNKNOWN,
        };
    }


    /**
     * @param  Node  $node
     * @return static
     */
    public static function fromNode(Node $node): self
    {
        return match (true) {
            $node instanceof Class_ => $node->isAbstract() ? self::TYPE_ABSTRACT : self::TYPE_CLASS,
            $node instanceof Interface_ => self::TYPE_INTERFACE,
            $node instanceof Enum_ => self::TYPE_ENUM,
            $node instanceof Trait_ => self::TYPE_TRAIT,
            default => self::TYPE_UNKNOWN,
        };
    }


    /**
     * @param  int  $value
     * @return static
     */
    public static function fromValue(int $value): self
    {
        return self::tryFrom($value) ?? self::TYPE_UNKNOWN;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * @return string
     */
    public function getShortName(): string
    {
        return substr($this->name, 5);
    }


    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }
}
