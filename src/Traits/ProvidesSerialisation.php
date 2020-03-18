<?php

namespace Tlr\Phpnum\Traits;

trait ProvidesSerialisation
{
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
}
