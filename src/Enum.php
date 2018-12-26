<?php

namespace Tlr\Phpnum;

use Illuminate\Support\Collection;
use ReflectionClass;
use Stringy\Stringy;

class Enum extends Core
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
     * @return array
     */
    public static function generateEnums() : array
    {
        if (static::$enum) {
            return static::$enum;
        }

        $reflection = new ReflectionClass(static::class);

        return $reflection->getConstants();
    }
}
