<?php

namespace Tlr\Phpnum\Traits;

use ReflectionClass;

trait GeneratesEnums
{
    /**
     * One way to define the enums
     *
     * @var array
     */
    protected static $enum = [];

    /**
     * Get the values for the enum
     *
     * The results of this get cacheds
     * @see ResolvesAndCaches
     *
     * @return array
     */
    public static function generateEnums() : array
    {
        if (static::$enum) {
            return static::$enum;
        }

        return static::getConstants();
    }

    /**
     * Get the constants
     *
     * @return array
     */
    public static function getConstants()
    {
        $reflection = new ReflectionClass(static::class);

        return $reflection->getConstants();
    }
}
