<?php

namespace Tlr\Phpnum\Traits;

use Stringy\Stringy;
use Tlr\Phpnum\Contracts\Enum;

trait StaticAccessors
{
    /**
     * Get the possible names / keys
     *
     * @return array
     */
    public static function names() : array
    {
        return array_keys(static::values());
    }

    /**
     * Get the possible raw values (no keys/names)
     *
     * @return array
     */
    public static function pureValues() : array
    {
        return array_values(static::values());
    }

    /**
     * Convert the given value to a friendly value
     *
     * @param  string $name
     * @return string
     */
    protected static function friendlifier(string $name) : string
    {
        return Stringy::create($name)
            ->humanize()
            ->titleize()
            ;
    }

    /**
     * Get a list of all resolved valid enums
     *
     * @return array
     */
    public static function all() : array
    {
        return array_map(function ($value) {
            return new static($value);
        }, static::values());
    }

    /**
     * Get the friendly value map
     *
     * @return array
     */
    public static function friendlyNames() : array
    {
        $values = static::values();

        $keys   = array_keys($values);
        $values = array_values($values);
        $keys   = array_map(function ($key) {
            return static::friendlifier($key);
        }, $keys);

        return array_combine($keys, $values);
    }

    /**
     * Check if the key exists on the enum
     *
     * @param  string  $key
     * @return boolean
     */
    public static function has(string $key) : bool
    {
        return in_array($key, static::values());
    }

    /**
     * Check if the key/name exists on the enum
     *
     * @param  string  $key
     * @return boolean
     */
    public static function hasName(string $key) : bool
    {
        return isset(static::values()[$key]);
    }

    /**
     * Get a random instantiated enum
     *
     * @return Enum
     */
    public static function random() : Enum
    {
        return new static(array_rand(array_flip(static::pureValues())));
    }
}
