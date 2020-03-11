<?php

namespace Tlr\Phpnum\Core;

use BadMethodCallException;
use JsonSerializable;
use Serializable;
use Stringy\Stringy;
use Tlr\Phpnum\Traits\ResolvesAndCallsMethodsFromName;
use UnexpectedValueException;

abstract class Core implements JsonSerializable, Serializable
{
    use ResolvesAndCallsMethodsFromName;

    /**
     * The core enum value
     *
     * @var string
     */
    private $value;

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
     * Set the value
     *
     * @param mixed $value
     * @return Core
     */
    private function setValue($value) : Core
    {
        if ($value instanceof Core) {
            $value = $value->value();
        }

        $this->value = static::checkValue($value);

        return $this;
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
     * Check if the enum is any of the provided enums
     *
     * @param Core ...$enums
     *
     * @return bool
     */
    public function isOneOf(Core ...$enums)
    {
        foreach ($enums as $enum) {
            if ($this->is($enum)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Included for compatability with myclabs library. Preferred comparison
     * method is ->is($enum)
     *
     * @param  Core   $enum
     *
     * @return bool
     * @deprecated use ->is($enum)
     */
    public function equals(?Core $enum) : bool
    {
        return $this->is($enum);
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
     * @return Core
     */
    public static function random() : Core
    {
        return new static(array_rand(array_flip(static::pureValues())));
    }

    /**
     * Calls a type specific method
     *
     * @param string $action
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call(string $action, array $arguments)
    {
        // A fix for a strange issue where php sometimes uses __call instead of __callStatic for static calls.
        if (static::hasName($action)) {
            return new static(static::values()[$action]);
        }

        return $this->callMethodForType($action, ...$arguments);
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
        if (static::hasName($name)) {
            return new static(static::values()[$name]);
        }

        throw new BadMethodCallException(
            sprintf(
                'No static method or enum constant for [%s] in enum [%s]',
                $name,
                static::class
            )
        );
    }
}
