<?php

namespace Tlr\PhpnumTests\Doubles;

use Tlr\Phpnum\Enum;

/**
 * Class FooBar
 *
 * @package Tlr\PhpnumTests\Doubles
 * @method static FooBarStatic FOO()
 * @method static FooBarStatic BAR()
 * @method static FooBarStatic BAZ()
 * @method static FooBarStatic MONKEYS()
 */
class FooBarStatic extends Enum
{
    protected static $enum = [
        'FOO'     => 'foo',
        'BAR'     => 'bar',
        'BAZ'     => 'baz',
        'MONKEYS' => 'monkeys',
    ];
}
