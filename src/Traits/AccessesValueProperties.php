<?php

namespace Tlr\Phpnum\Traits;

use Stringy\Stringy;
use Tlr\Phpnum\Contracts\Enum;

trait AccessesValueProperties
{
    /**
     * Get the name
     *
     * @return string
     */
    public function name() : string
    {
        return array_flip(static::values())[$this->value()];
    }

    /**
     * Get the friendly name
     *
     * @return string
     */
    public function friendlyName() : string
    {
        return array_flip(static::friendlyNames())[$this->value];
    }

    /**
     * Convert to a string
     *
     * @return string
     */
    public function toString() : string
    {
        return (string) $this->value;
    }

    /**
     * Convert to a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
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
     * Get a random instantiated enum
     *
     * @return Enum
     */
    public static function random() : Enum
    {
        return new static(array_rand(array_flip(static::pureValues())));
    }

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
    protected static function friendlify(string $name) : string
    {
        return Stringy::create($name)
            ->humanize()
            ->titleize()
            ;
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
            return static::friendlify($key);
        }, $keys);

        return array_combine($keys, $values);
    }
}
