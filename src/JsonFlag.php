<?php

namespace Tlr\Phpnum;

use Tlr\Phpnum\Core\Flag as Core;
use Tlr\Phpnum\Traits\ReflectsFromConstants;

abstract class JsonFlag extends Core
{
    use ReflectsFromConstants;

    /**
     * One way to define the enums
     *
     * @var array
     */
    protected static $flags = [];

    /**
     * Get the values for the enum
     *
     * @return array
     */
    public static function generateEnums() : array
    {
        if (static::$flags) {
            return static::$flags;
        }

        return static::getConstants();
    }

    /**
     * Reduce the given array of flags into a single flag value
     *
     * @param  array  $flags
     * @return Tlr\Phpnum\Core\Flag
     */
    public static function combineFlags(array $flags) : Core
    {
        $value = array_reduce($flags, function(array $carry, Core $flag) {
            if (!$flag instanceof static) {
                throw new UnexpectedValueException(sprintf(
                    'Cannot merge flag type [%s] into [%s]',
                    get_class($flag),
                    static::class
                ));
            }

            $carry[] = $flag->value();

            return $carry;
        }, []);

        return new static(json_encode($value));
    }

    /**
     * Check if the current flag contains any of the passed in ones
     *
     * @param  ...Tlr\Phpnum\Flag  $flag
     * @return boolean
     */
    public function matches(...$flags) : bool
    {
        return static::combineFlags($flags)->value() & $this->value();
    }

    /**
     * Check if the current flag contains all of the passed in ones
     *
     * @param  ...Tlr\Phpnum\Flag  $flag
     * @return boolean
     */
    public function matchesAll(...$flags) : bool
    {
        return static::combineFlags($flags)->value() === $this->value();
    }

    /**
     * Check that a given value is valid.
     *
     * @param  mixed $value
     * @return bool
     */
    public static function isValidValue($value) : bool
    {
        $value = json_decode((string) $value);

        // check is array


        // array diff / intersect against static::pureValues() to check for foreign values

        $isValidZero = $value === 0 && in_array(0, static::pureValues());
        $isValidFlag = $value & static::flagValueForAll();

        return $isValidZero || $isValidFlag;
    }

    /**
     * Get the value of the
     *
     * @param  ...Tlr\Phpnum\Flag  $flag
     * @return int
     */
    public static function flagValueForAll() : string
    {
        return array_reduce(array_values(static::values()), function (int $carry, $value) {
            return $carry | $value;
        }, 0);
    }

    /**
     * Check if the current flag contains all of the passed in ones
     *
     * @param  ...Tlr\Phpnum\Core\Flag  $flag
     * @return boolean
     */
    public static function flagForAll() : Core
    {
        return new static(static::flagValueForAll());
    }
}
