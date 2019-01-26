<?php

namespace Tlr\Phpnum\Traits;

use ReflectionClass;

trait ReflectsFromConstants
{
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
