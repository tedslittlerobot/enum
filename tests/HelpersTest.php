<?php

use PHPUnit\Framework\TestCase;
use Tlr\Phpnum\Enum;
use function Tlr\Phpnum\{enum_getter, enum_setter};

class TestEnumHelpers extends Enum
{
    const A = 1;
    const B = 2;
    const C = 3;
    const D = 'dee';
}

class HelpersTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEnumGetter()
    {
        $this->assertNull(enum_getter(TestEnumHelpers::class, null));
        $this->assertNull(enum_getter(TestEnumHelpers::class));

        $this->assertSame(2, enum_getter(TestEnumHelpers::class, 2)->value());
        $this->assertSame(2, enum_getter(TestEnumHelpers::class, '2')->value());

        $origin = TestEnumHelpers::B();
        $this->assertSame(2, enum_getter(TestEnumHelpers::class, $origin)->value());
        $this->assertNotSame($origin, enum_getter(TestEnumHelpers::class, $origin)->value());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEnumSetter()
    {
        $this->assertNull(enum_setter(TestEnumHelpers::class, null, true));

        $origin = TestEnumHelpers::B();
        $this->assertSame(2, enum_setter(TestEnumHelpers::class, $origin));

        $this->assertSame(2, enum_setter(TestEnumHelpers::class, 2));
        $this->assertSame(2, enum_setter(TestEnumHelpers::class, '2'));
        $this->assertSame('dee', enum_setter(TestEnumHelpers::class, 'dee'));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNonNullableEnumSetter()
    {
        $this->expectException(UnexpectedValueException::class);

        enum_setter(TestEnumHelpers::class, null);
    }
}
