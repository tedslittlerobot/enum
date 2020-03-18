<?php

namespace Tlr\PhpnumTests\Traits;

use PHPUnit\Framework\TestCase;
use Tlr\PhpnumTests\Doubles\FooBar;
use Tlr\PhpnumTests\Doubles\SuperstitiousNumbers;

class AccessesValuePropertiesTest extends TestCase
{
    /**
     * @test
     */
    public function static_names_returnsAllNames()
    {
        $this->assertEquals(
            [
                'FOO', 'BAR', 'BAZ', 'MONKEYS',
            ],
            FooBar::names()
        );
    }

    /**
     * @test
     */
    public function friendlyName_returnsFriendlyName()
    {
        $enum = SuperstitiousNumbers::KA_TET();

        $this->assertEquals('Ka Tet', $enum->friendlyName());
    }

    /**
     * @test
     */
    public function toString_convertsToString()
    {
        $enum = FooBar::MONKEYS();

        $this->assertSame('monkeys', (string) $enum);

        $enum = SuperstitiousNumbers::KA_TET();

        $this->assertSame('19', (string) $enum);
    }


    /**
     * @test
     */
    public function all_returnsAllValues()
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
    public function random_returnsRandomValidEnum()
    {
        $enum = FooBar::random();

        $this->assertInstanceOf(FooBar::class, $enum);
    }
}
