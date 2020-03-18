<?php

namespace Tlr\Phpnum\Contracts;

interface EnumComparisons
{
    /**
     * Check if the enum is any of the provided enums
     *
     * @param Enum ...$enums
     *
     * @return bool
     */
    public function isOneOf(Enum ...$enums);

    /**
     * Compare against another enum
     *
     * @param  Enum $enum
     *
     * @return boolean
     */
    public function is(?Enum $enum) : bool;

    /**
     * Check if the key exists on the enum
     *
     * @param  string  $key
     * @return boolean
     */
    public static function has(string $key) : bool;

    /**
     * Check if the key/name exists on the enum
     *
     * @param  string  $key
     * @return boolean
     */
    public static function hasName(string $key) : bool;
}
