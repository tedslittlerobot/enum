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

class EnumTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetAllNames()
    {
        $this->assertEquals([
            'FOO', 'BAR', 'BAZ', 'MONKEYS',
        ], FooBar::names());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStaticInstantiation()
    {
        $this->assertEquals([
            'FOO', 'BAR', 'BAZ', 'MONKEYS',
        ], FooBarStatic::names());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testConstInstantiationConflicts()
    {
        $this->assertEquals([
            'ONE',
        ], ConflictOne::names());

        $this->assertEquals([
            'TWO',
        ], ConflictTwo::names());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCanInstantiate()
    {
        $enum = new FooBar('foo');

        $this->assertInstanceOf(Enum::class, $enum);
        $this->assertEquals('FOO', $enum->name());
        $this->assertEquals('foo', $enum->value());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFailsInstantiation()
    {
        $this->expectException(UnexpectedValueException::class);

        $enum = new SuperstitiousNumbers('π');
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFriendlyName()
    {
        $enum = SuperstitiousNumbers::KA_TET();

        $this->assertEquals('Ka Tet', $enum->friendlyName());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCanMagicallyInstantiate()
    {
        $enum = FooBar::MONKEYS();

        $this->assertInstanceOf(FooBar::class, $enum);
        $this->assertEquals('monkeys', $enum->value());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCannotMagicallyInstantiateSomethingThatDoesntExist()
    {
        $this->expectException(BadMethodCallException::class);

        $enum = SuperstitiousNumbers::PI();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStringConversion()
    {
        $enum = FooBar::MONKEYS();

        $this->assertSame('monkeys', (string) $enum);

        $enum = SuperstitiousNumbers::KA_TET();

        $this->assertSame('19', (string) $enum);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEqualityComparison()
    {
        $enum = FooBar::MONKEYS();
        $other = FooBar::MONKEYS();

        $this->assertTrue($enum->is($other));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNullEqualityComparison()
    {
        $enum = FooBar::MONKEYS();
        $other = null;

        $this->assertFalse($enum->is($other));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEqualityComparisonFailsWhenSameClass()
    {
        $enum = FooBar::FOO();
        $other = FooBar::BAR();

        $this->assertFalse($enum->is($other));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEqualityComparisonFailsWhenDifferentClassWithSameValue()
    {
        $enum = FooBar::MONKEYS();
        $other = FooBarStatic::MONKEYS();

        $this->assertFalse($enum->is($other));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testInstantiationFromIdenticalEnum()
    {
        $enum = FooBar::MONKEYS();
        $other = new FooBar($enum);

        $this->assertTrue($enum->is($other));
        $this->assertFalse($enum === $other);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testInstantiationFromSimilarEnum()
    {
        $enum = FooBar::MONKEYS();
        $other = new FooBarStatic($enum);

        $this->assertFalse($enum->is($other));
        $this->assertFalse($enum === $other);
        $this->assertSame($enum->value(), $other->value());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testJsonEncoding()
    {
        $enum = FooBar::MONKEYS();

        $encoded = json_encode(['enum' => $enum]);

        $this->assertEquals('{"enum":"monkeys"}', $encoded);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSerialisation()
    {
        $enum = FooBar::MONKEYS();
        $encoded = serialize($enum);

        $this->assertEquals('C:30:"Tlr\PhpnumTests\Doubles\FooBar":9:{"monkeys"}', $encoded);

        $other = unserialize($encoded);

        $this->assertFalse($enum === $other);
        $this->assertTrue($enum->is($other));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAllAccessor()
    {
        $enums = FooBar::all();

        $this->assertCount(4, $enums);

        $this->assertTrue(FooBar::FOO()->is($enums['FOO']));
        $this->assertTrue(FooBar::BAR()->is($enums['BAR']));
        $this->assertTrue(FooBar::BAZ()->is($enums['BAZ']));
        $this->assertTrue(FooBar::MONKEYS()->is($enums['MONKEYS']));
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRandomAccessor()
    {
        $enum = FooBar::random();

        $this->assertInstanceOf(FooBar::class, $enum);
    }
}
