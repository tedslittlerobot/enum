<?php

namespace Tlr\Phpnum\Contracts;

interface EnumValueProvider
{
    /**
     * Get the values for the enum
     *
     * @return array
     */
    public static function generateEnums() : array;
}
