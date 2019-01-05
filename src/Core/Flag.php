<?php

namespace Tlr\Phpnum\Core;

use Tlr\Phpnum\Core\Core;

abstract class Flag extends Core
{
    /**
     * Reduce the given array of flags into a single flag value
     *
     * @param  array  $flags
     * @return Tlr\Phpnum\Flag
     */
    abstract public static function combineFlags(array $flags) : Flag;

    /**
     * Reduce the given array of flags into a single flag value
     *
     * @param  Tlr\Phpnum\Core\Flag  ...$flags
     * @return Tlr\Phpnum\Flag
     */
    public static function union(...$flags) : Flag
    {
        return static::combineFlags($flags);
    }

    /**
     * Get a flag that represents all of the available options
     *
     * @param  ...Tlr\Phpnum\Flag  $flag
     * @return boolean
     */
    abstract public static function flagForAll() : Flag;

    /**
     * Check if the current flag contains any of the passed in ones
     *
     * @param  ...Tlr\Phpnum\Flag  $flag
     * @return boolean
     */
    abstract public function matches(...$flags) : bool;

    /**
     * Check if the current flag contains all of the passed in ones
     *
     * @param  ...Tlr\Phpnum\Flag  $flag
     * @return boolean
     */
    abstract public function matchesAll(...$flags) : bool;

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
