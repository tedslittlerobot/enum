<?php

namespace Tlr\Phpnum\Contracts;

interface EnumAccessors
{
    /**
     * Get the value of the enum
     *
     * @return mixed
     */
    public function value();

    /**
     * Get the name
     *
     * @return string
     */
    public function name() : string;

    /**
     * Get the friendly name
     *
     * @return string
     */
    public function friendlyName() : string;

    /**
     * Convert to a string
     *
     * @return string
     */
    public function toString() : string;

    /**
     * Convert to a string
     *
     * @return string
     */
    public function __toString();

    /**
     * Get the values for the enum
     *
     * @return array
     */
    public static function values() : array;

    /**
     * Get the possible names / keys
     *
     * @return array
     */
    public static function names() : array;

    /**
     * Get the possible raw values (no keys/names)
     *
     * @return array
     */
    public static function pureValues() : array;

    /**
     * Get a list of all resolved valid enums
     *
     * @return array
     */
    public static function all() : array;
}
