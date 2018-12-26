<?php

namespace Tlr\Phpnum;

use BadMethodCallException;
use JsonSerializable;
use Serializable;
use Stringy\Stringy;
use UnexpectedValueException;

abstract class Core implements JsonSerializable, Serializable
{
    protected $value;

    public function __construct($value)
    {
        $this->setValue($value);
    }

    /**
     * Get the value
     *
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

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
    public function __toString()
    {
        return (string) $this->value;
    }

    /**
     * Compare against another enum
     *
     * @param  Core    $enum
     * @return boolean
     */
    public function is(?Core $enum) : bool
    {
        if (!$enum) {
            return false;
        }

        return $this->value() === $enum->value && static::class === get_class($enum);
    }

    /**
     * Included for compatability with myclabs library
     *
     * @param  Core   $enum
     * @return bool
     */
    public function equals(?Core $enum) : bool
    {
        return $this->is($enum);
    }

    /**
     * Set the value
     *
     * @param mixed $value
     */
    protected function setValue($value)
    {
        if ($value instanceof Core) {
            $value = $value->value();
        }

        $this->value = static::checkValue($value);

        return $this;
    }

    /**
     * Specify how the enum should be converted to JSON
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->value();
    }

    /**
     * PHP serialise the value
     *
     * @return string
     */
    public function serialize()
    {
        return json_encode($this->value());
    }

    /**
     * Handle deserialisation
     *
     * @param  string $serialized
     * @return void
     */
    public function unserialize($serialized)
    {
        $this->setValue(json_decode($serialized));
    }

    ////////////////////////////// STATIC METHODS //////////////////////////////

    /**
     * Get the values for the enum
     *
     * @return array
     */
    abstract public static function generateEnums() : array;

    /**
     * The cached map of raw enum values
     *
     * This needs to go in a class-keyed cache due to the way that PHP resolves
     * static properties
     *
     * @var array
     */
    protected static $cache = [];

    /**
     * Get the values for the enum
     *
     * @return array
     */
    public static function values() : array
    {
        if (!isset(static::$cache[static::class])) {
            static::$cache[static::class] = static::generateEnums();
        }

        return static::$cache[static::class];
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
    protected static function friendlifier(string $name) : string
    {
        return Stringy::create($name)
            ->humanize()
            ->titleize()
        ;

        return $name;
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
     * Check that a given value is valid.
     *
     * @param  mixed $value
     * @return bool
     */
    public static function isValidValue($value) : bool
    {
        return in_array($value, static::pureValues());
    }

    /**
     * Check that a given value is valid. Returns the value if it is, otherwise
     * throws
     *
     * @throws UnexpectedValueException
     * @param  mixed $value
     * @return mixed
     */
    public static function checkValue($value)
    {
        if (!static::isValidValue($value)) {
            throw new UnexpectedValueException(sprintf(
                'Value [%s] is not part of the enum [%s]',
                $value,
                static::class
            ));
        }

        return $value;
    }

    /**
     * Check if the key/name exists on the enum
     *
     * @param  string  $key
     * @return boolean
     */
    public static function has(string $key) : bool
    {
        return isset(static::values()[$key]);
    }

    /**
     * Get a random instantiated enum
     *
     * @return Tlr\Phpnum\Core
     */
    public static function random() : Core
    {
        return new static(array_rand(array_flip(static::pureValues())));
    }

    /**
     * Dynamically instantiate instances of the enum statically
     *
     * @param  string $name
     * @param  array  $args
     * @return Core
     */
    public static function __callStatic($name, $args)
    {
        if (static::has($name)) {
            return new static(static::values()[$name]);
        }

        throw new BadMethodCallException(sprintf(
            'No static method or enum constant for [%s] in enum [%s]',
            $name,
            static::class
        ));
    }
}
