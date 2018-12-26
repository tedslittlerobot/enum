<?php

namespace Tlr\Phpnum;

use Tlr\Phpnum\Core;

/**
 * Helper for model getters using enums
 *
 * @param  string $class
 * @param  mixed $value
 * @return ?Core
 */
function enum_getter(string $class, $value = null) : ?Core
{
    if (is_numeric($value)) {
        $value = (int) $value;
    }

    if ($value instanceof Core) {
        $value = $value->value();
    }

    return (!is_null($value)) ? new $class($value) : null;
}

/**
 * Helper for model setters using enums
 *
 * @param  string       $class
 * @param  mixed       $value
 * @param  bool|boolean $nullable
 * @return mixed
 */
function enum_setter(string $class, $value, bool $nullable = false)
{
    if ($nullable && is_null($value)) {
        return null;
    }

    if (is_numeric($value)) {
        $value = (int) $value;
    }

    if (!$value instanceof $class) {
        $value = new $class($value);
    }

    return $value->value();
}
