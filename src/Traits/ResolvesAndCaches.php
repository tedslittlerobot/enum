<?php

namespace Tlr\Phpnum\Traits;

use UnexpectedValueException;

trait ResolvesAndCaches
{
    /**
     * The cached map of raw enum values
     *
     * This needs to go in a class-keyed cache due to the way that PHP resolves
     * static properties
     *
     * @var array
     */
    protected static $cache = [];

    /**
     * Get the values for the enum
     *
     * @return array
     */
    public static function values() : array
    {
        if (!isset(static::$cache[static::class])) {
            static::$cache[static::class] = static::generateEnums();
        }

        return static::$cache[static::class];
    }

    /**
     * Check that a given value is valid.
     *
     * @param  mixed $value
     * @return bool
     */
    public static function isValidValue($value) : bool
    {
        return in_array($value, static::pureValues());
    }

    /**
     * Check that a given value is valid. Returns the value if it is, otherwise
     * throws
     *
     * @throws UnexpectedValueException
     * @param  mixed $value
     * @return mixed
     */
    public static function checkValue($value)
    {
        if (!static::isValidValue($value)) {
            throw new UnexpectedValueException(
                sprintf(
                    'Value [%s] is not part of the enum [%s]',
                    $value,
                    static::class
                )
            );
        }

        return $value;
    }
}
