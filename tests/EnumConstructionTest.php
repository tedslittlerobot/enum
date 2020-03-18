<?php

namespace Tlr\PhpnumTests;

use BadMethodCallException;
use PHPUnit\Framework\TestCase;
use Tlr\Phpnum\Contracts\Enum;
use Tlr\PhpnumTests\Doubles\ConflictOne;
use Tlr\PhpnumTests\Doubles\ConflictTwo;
use Tlr\PhpnumTests\Doubles\FooBar;
use Tlr\PhpnumTests\Doubles\FooBarStatic;
use Tlr\PhpnumTests\Doubles\SuperstitiousNumbers;
use UnexpectedValueException;

class EnumConstructionTest extends TestCase
{
    /**
     * @test
     */
    public function constructor_withConflictingEnumsInStaticCache_resolvesCorrectly()
    {
        $this->assertEquals([
            'ONE',
        ], ConflictOne::names());

        $this->assertEquals([
            'TWO',
        ], ConflictTwo::names());
    }

    /**
     * @test
     */
    public function constructor_withValueValue_instantiates()
    {
        $enum = new FooBar('foo');

        $this->assertInstanceOf(Enum::class, $enum);
        $this->assertEquals('FOO', $enum->name());
        $this->assertEquals('foo', $enum->value());
    }

    /**
     * @test
     */
    public function constructor_withInvalueValue_instantiates()
    {
        $this->expectException(UnexpectedValueException::class);

        $enum = new SuperstitiousNumbers('π');
    }

    /**
     * @test
     */
    public function staticConstConstructor_fromValidConst_instantiates()
    {
        $enum = FooBar::MONKEYS();

        $this->assertInstanceOf(FooBar::class, $enum);
        $this->assertEquals('monkeys', $enum->value());
    }

    /**
     * @test
     */
    public function staticConstConstructor_fromInvalidConst_errors()
    {
        $this->expectException(BadMethodCallException::class);

        $enum = SuperstitiousNumbers::PI();
    }

    /**
     * @test
     */
    public function constructor_fromIdenticalEnum_instantiates()
    {
        $enum = FooBar::MONKEYS();
        $other = new FooBar($enum);

        $this->assertTrue($enum->is($other));
        $this->assertFalse($enum === $other);
    }

    /**
     * @test
     */
    public function constructor_fromSimilarEnum_instantiates()
    {
        $enum = FooBar::MONKEYS();
        $other = new FooBarStatic($enum);

        $this->assertFalse($enum->is($other));
        $this->assertFalse($enum === $other);
        $this->assertSame($enum->value(), $other->value());
    }
}
