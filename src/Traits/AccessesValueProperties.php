<?php

namespace Tlr\Phpnum\Traits;

use Tlr\PhpnumTests\Doubles\FooBar;

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

    /**
     * @test
     */
    public function allAccessor()
    {
        $enums = FooBar::all();

        $this->assertCount(4, $enums);

        $this->assertTrue(FooBar::FOO()->is($enums['FOO']));
        $this->assertTrue(FooBar::BAR()->is($enums['BAR']));
        $this->assertTrue(FooBar::BAZ()->is($enums['BAZ']));
        $this->assertTrue(FooBar::MONKEYS()->is($enums['MONKEYS']));
    }

    /**
     * @test
     */
    public function randomAccessor()
    {
        $enum = FooBar::random();

        $this->assertInstanceOf(FooBar::class, $enum);
    }
}
