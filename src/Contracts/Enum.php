<?php

namespace Tlr\Phpnum\Contracts;

use JsonSerializable;
use Serializable;

interface Enum extends EnumAccessors, EnumComparisons, JsonSerializable, Serializable
{
    /**
     * Get the values for the enum
     *
     * @return array
     */
    public static function generateEnums() : array;

    /**
     * Get a random instantiated enum
     *
     * @return Enum
     */
    public static function random() : Enum;
}
