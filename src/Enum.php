<?php

namespace Tlr\Phpnum;

use Illuminate\Support\Collection;
use Tlr\Phpnum\Core\Core;
use Tlr\Phpnum\Traits\ResolvesAndCallsMethodsFromName;
use Tlr\Phpnum\Traits\ReflectsFromConstants;

abstract class Enum extends Core
{
    use ReflectsFromConstants, ResolvesAndCallsMethodsFromName;

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

        return static::getConstants();
    }
}
