<?php

namespace Envorra\ClassFinder\Helpers;

use ReflectionException;

/**
 * ObjectHelper
 *
 * @package  Envorra\ClassFinder\Helpers
 *
 * @template TObject of object
 *
 * @extends ReflectionHelper<TObject>
 */
class ObjectHelper extends ReflectionHelper
{
    /**
     * @param  TObject       $object
     * @param  class-string  $class
     * @return bool
     */
    public static function objectInstanceMatches(object $object, string $class): bool
    {
        return $object instanceof $class;
    }

    /**
     * @param  TObject         $object
     * @param  class-string[]  $classes
     * @return bool
     */
    public static function objectInstanceMatchesAll(object $object, array $classes): bool
    {
        $matches = !empty($classes);
        foreach ($classes as $class) {
            $matches = $matches && static::objectInstanceMatches($object, $class);
        }
        return $matches;
    }

    /**
     * @param  TObject         $object
     * @param  class-string[]  $classes
     * @return bool
     */
    public static function objectInstanceMatchesOne(object $object, array $classes): bool
    {
        foreach ($classes as $class) {
            if (static::objectInstanceMatches($object, $class)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param  string  $property
     * @return mixed
     */
    public function getProperty(string $property): mixed
    {
        if (isset($this->reflected->$property)) {
            return $this->reflected->$property;
        }

        if ($this->hasProperty($property, self::MODIFIER_PROTECTED | self::MODIFIER_PRIVATE)) {
            try {
                return $this->reflection->getProperty($property)->getValue($this->reflected);
            } catch (ReflectionException) {
                // move on
            }
        }

        return null;
    }

    /**
     * @param  class-string  $class
     * @return bool
     */
    public function instanceMatches(string $class): bool
    {
        return static::objectInstanceMatches($this->reflected, $class);
    }

    /**
     * @param  class-string[]  $classes
     * @return bool
     */
    public function instanceMatchesAll(array $classes): bool
    {
        return static::objectInstanceMatchesAll($this->reflected, $classes);
    }

    /**
     * @param  class-string[]  $classes
     * @return bool
     */
    public function instanceMatchesOne(array $classes): bool
    {
        return static::objectInstanceMatchesOne($this->reflected, $classes);
    }
}
