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

    /**
     * @test
     */
    public function isOneOf_withSeveralInvalidEnums_andOneValid_passes()
    {
        $enum = FooBar::MONKEYS();

        $this->assertTrue($enum->isOneOf(FooBar::FOO(), FooBar::BAR(), FooBar::MONKEYS()));
    }

    /**
     * @test
     */
    public function isOneOf_withSeveralInvalidEnums_fails()
    {
        $enum = FooBar::MONKEYS();

        $this->assertFalse($enum->isOneOf(FooBar::FOO(), FooBar::BAR()));
    }

    /**
     * @test
     */
    public function static_has_withValidValue_returnsTrue()
    {
        $this->assertTrue(FooBar::has('foo'));
        $this->assertTrue(FooBar::has('bar'));
        $this->assertTrue(FooBar::has('baz'));
        $this->assertTrue(FooBar::has('monkeys'));
    }

    /**
     * @test
     */
    public function static_has_withInvalidValue_returnsFalse()
    {
        $this->assertFalse(FooBar::has('woop'));
    }
}
