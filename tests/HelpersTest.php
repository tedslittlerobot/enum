<?php

namespace Tlr\PhpnumTests;

use PHPUnit\Framework\TestCase;
use function Tlr\Phpnum\{enum_getter, enum_setter};
use Tlr\PhpnumTests\Doubles\OneTwoThreeD;
use UnexpectedValueException;

class HelpersTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEnumGetter()
    {
        $this->assertNull(enum_getter(OneTwoThreeD::class, null));
        $this->assertNull(enum_getter(OneTwoThreeD::class));

        $this->assertSame(2, enum_getter(OneTwoThreeD::class, 2)->value());
        $this->assertSame(2, enum_getter(OneTwoThreeD::class, '2')->value());

        $origin = OneTwoThreeD::TWO();
        $this->assertSame(2, enum_getter(OneTwoThreeD::class, $origin)->value());
        $this->assertNotSame($origin, enum_getter(OneTwoThreeD::class, $origin)->value());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEnumSetter()
    {
        $this->assertNull(enum_setter(OneTwoThreeD::class, null, true));

        $origin = OneTwoThreeD::TWO();
        $this->assertSame(2, enum_setter(OneTwoThreeD::class, $origin));

        $this->assertSame(2, enum_setter(OneTwoThreeD::class, 2));
        $this->assertSame(2, enum_setter(OneTwoThreeD::class, '2'));
        $this->assertSame('d', enum_setter(OneTwoThreeD::class, 'd'));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNonNullableEnumSetter()
    {
        $this->expectException(UnexpectedValueException::class);

        enum_setter(OneTwoThreeD::class, null);
    }
}
