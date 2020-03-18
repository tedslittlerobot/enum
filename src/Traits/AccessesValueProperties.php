<?php

namespace Tlr\Phpnum\Traits;

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
}
