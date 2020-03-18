<?php

namespace Tlr\PhpnumTests\Doubles;

use Tlr\Phpnum\Enum;

/**
 * Class FooBar
 *
 * @package Tlr\PhpnumTests\Doubles
 * @method static FooBar FOO()
 * @method static FooBar BAR()
 * @method static FooBar BAZ()
 * @method static FooBar MONKEYS()
 *
 * @method FooBar prepend(string $prepend)
 */
class FooBar extends Enum
{
    const FOO = 'foo';
    const BAR = 'bar';
    const BAZ = 'baz';
    const MONKEYS = 'monkeys';

    public function prependForFoo(string $value) : string
    {
        return "$value-foo";
    }

    public function prependForBar(string $value) : string
    {
        return "$value-bar";
    }

    public function prependForBaz(string $value) : string
    {
        return "$value-baz";
    }
}
