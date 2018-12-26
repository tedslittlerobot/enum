<?php

namespace Tlr\Phpnum;

use ReflectionClass;
use Tlr\Phpnum\Core;
use UnexpectedValueException;

/**
 * @todo  - acccept intermediary values (not above maximum bit range - ie. must
 * mask to at least one valid value - override static::checkValue())
 * @todo  - compare masks (matches() method)
 * @todo  - compare masks (matchesAll() method)
 * @todo  - method to separate into separate bit mask values (flags())
 */
class Flag extends Core
{
    /**
     * Should there be a zero value
     *
     * @var string
     */
    protected static $zero;

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
            return static::generateFromStaticValues();
        }

        $reflection = new ReflectionClass(static::class);

        return $reflection->getConstants();
    }

    /**
     * Get some enums from the static values
     *
     * @return array
     */
    public static function generateFromStaticValues() : array
    {
        $flags = [];

        if (static::$zero) {
            $flags[static::$zero] = 0;
        }

        foreach (array_values(static::$flags) as $index => $flag) {
            // Ensure powers of 2 (ie. bit flags)
            $flags[$flag] = 2 ** $index;
        }

        return $flags;
    }

    /**
     * Check that a given value is valid.
     *
     * @param  mixed $value
     * @return bool
     */
    public static function isValidValue($value) : bool
    {
        $values = static::pureValues();

        $lower = head($values);
        $nextBit = end($values) * 2;

        return $value >= $lower && $value < $nextBit;
    }

    /**
     * Reduce the given array of flags into a single flag value
     *
     * @param  array  $flags
     * @return Tlr\Phpnum\Flag
     */
    public static function combineFlags(array $flags) : Flag
    {
        $value = array_reduce($flags, function(int $carry, Flag $flag) {
            if (!$flag instanceof static) {
                throw new UnexpectedValueException(sprintf(
                    'Cannot merge flag type [%s] into [%s]',
                    get_class($flag),
                    static::class
                ));
            }

            return $carry | $flag->value();
        }, 0);

        return new static($value);
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
        return (int) static::combineFlags($flags)->value() === (int) $this->value();
    }

    /**
     * Get all of the flags contained within the current value
     *
     * @return array<Tlr\Phpnum\Flag>
     */
    public function matchedFlags() : array
    {
        return array_values(array_filter(static::all(), function(Flag $value) {
            return $this->matches($value);
        }));
    }
}
