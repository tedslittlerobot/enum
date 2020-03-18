<?php

namespace Tlr\PhpnumTests\Traits;

use PHPUnit\Framework\TestCase;
use Tlr\PhpnumTests\Doubles\FooBar;
use Tlr\PhpnumTests\Doubles\FooBarStatic;

class ComparesAgainstEnumsTest extends TestCase
{
    /**
     * @test
     */
    public function is_withIdenticalEnum_returnsTrue()
    {
        $enum = FooBar::MONKEYS();
        $other = FooBar::MONKEYS();

        $this->assertTrue($enum->is($other));
    }

    /**
     * @test
     */
    public function is_withNull_returnsFalse()
    {
        $enum = FooBar::MONKEYS();
        $other = null;

        $this->assertFalse($enum->is($other));
    }

    /**
     * @test
     */
    public function is_withOtherValue_returnsFalse()
    {
        $enum = FooBar::FOO();
        $other = FooBar::BAR();

        $this->assertFalse($enum->is($other));
    }

    /**
     * @test
     */
    public function is_withOtherEnum_andSameValue_returnsFalse()
    {
        $enum = FooBar::MONKEYS();
        $other = FooBarStatic::MONKEYS();

        $this->assertFalse($enum->is($other));
    }
}
