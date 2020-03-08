<?php

namespace Tlr\Phpnum\Traits;

use BadMethodCallException;

trait ResolvesAndCallsMethodsFromName
{
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
        $method = $this->methodForType($action);

        if (!method_exists($this, $method)) {
            throw new BadMethodCallException("No such method [$action] or [$method]");
        }

        return $this->{$method}(...$arguments);
    }

    /**
     * Generate a method for the given action and the internal name of the enum.
     *
     * For example, when passed the action "resolve", and the internal enum name
     * FOO_BAR, this method will return "resolveForFooBar".
     *
     * @param string $action
     *
     * @return string
     */
    public function methodForType(string $action) : string
    {
        $value_string = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $this->name())));

        return "{$action}For{$value_string}";
    }
}
