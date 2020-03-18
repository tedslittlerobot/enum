<?php

namespace Tlr\Phpnum\Traits;

use Tlr\Phpnum\Contracts\Enum;

trait ComparesAgainstEnums
{
    /**
     * Compare against another enum
     *
     * @param  Enum $enum
     *
     * @return boolean
     */
    public function is(?Enum $enum) : bool
    {
        if (!$enum) {
            return false;
        }

        return $this->value() === $enum->value() && static::class === get_class($enum);
    }

    /**
     * Check if the enum is any of the provided enums
     *
     * @param Enum ...$enums
     *
     * @return bool
     */
    public function isOneOf(Enum ...$enums)
    {
        foreach ($enums as $enum) {
            if ($this->is($enum)) {
                return true;
            }
        }

        return false;
    }
}
