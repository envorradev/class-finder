<?php

namespace Envorra\ClassFinder\Enums;

use PhpParser\Node;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Stmt\Interface_;
use Envorra\ClassFinder\Contracts\ClassType;

/**
 * Type
 *
 * @package Envorra\ClassFinder\Enums
 */
enum Type: int implements ClassType
{
    case TYPE_UNKNOWN = 0;
    case TYPE_CLASS = 1;
    case TYPE_ABSTRACT = 2;
    case TYPE_INTERFACE = 4;
    case TYPE_ENUM = 8;
    case TYPE_TRAIT = 16;

    // Integer values below are calculated by using bitwise 'OR' on the relevant cases.
    // BE SURE TO UPDATE THESE IF ANY OTHER TYPES ARE ADDED!
    case TYPE_INSTANTIABLE = 9;
    case TYPE_NON_INSTANTIABLE = 22;
    case TYPE_ANY = 31;

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public static function fromNode(Node $node): self
    {
        if ($node instanceof Class_) {
            if ($node->isAbstract()) {
                return self::TYPE_ABSTRACT;
            }

            return self::TYPE_CLASS;
        }

        if ($node instanceof Interface_) {
            return self::TYPE_INTERFACE;
        }

        if ($node instanceof Enum_) {
            return self::TYPE_ENUM;
        }

        if ($node instanceof Trait_) {
            return self::TYPE_TRAIT;
        }

        return self::TYPE_UNKNOWN;
    }

    /**
     * @inheritDoc
     */
    public static function fromValue(int $value): self
    {
        return self::tryFrom($value) ?? self::TYPE_UNKNOWN;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getShortName(): string
    {
        return substr($this->name, 5);
    }

    /**
     * @inheritDoc
     */
    public function getValue(): int
    {
        return $this->value;
    }


}
