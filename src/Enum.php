<?php

namespace Tlr\Phpnum;

use BadMethodCallException;
use Tlr\Phpnum\Contracts\Enum as EnumContract;
use Tlr\Phpnum\Traits\AccessesValueProperties;
use Tlr\Phpnum\Traits\CallsEnumerableMethods;
use Tlr\Phpnum\Traits\ComparesAgainstEnums;
use Tlr\Phpnum\Traits\GeneratesEnums;
use Tlr\Phpnum\Traits\ProvidesSerialisation;
use Tlr\Phpnum\Traits\ResolvesAndCaches;

abstract class Enum implements EnumContract
{
    use AccessesValueProperties,
        ComparesAgainstEnums,
        ProvidesSerialisation,
        CallsEnumerableMethods,
        GeneratesEnums,
        ResolvesAndCaches;

    /**
     * The core enum value
     *
     * @var string
     */
    private $value;

    /**
     * Enum constructor.
     *
     * @param $value
     */
    public function __construct($value)
    {
        $this->setValue($value);
    }

    /**
     * Set the value
     *
     * @param mixed $value
     *
     * @return Enum
     */
    private function setValue($value) : Enum
    {
        if ($value instanceof Enum) {
            $value = $value->value();
        }

        $this->value = static::checkValue($value);

        return $this;
    }

    /**
     * Get the value of the enum
     *
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    ////////////////////////////// STATIC METHODS //////////////////////////////

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
     *
     * @return Enum
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
