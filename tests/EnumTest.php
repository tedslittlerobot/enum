<?php

use PHPUnit\Framework\TestCase;
use Tlr\Phpnum\Enum;

class TestClassEnum extends Enum
{
    protected static $enum = [
        'ONE'        => 'a',
        'TWO'        => 'b',
        'THREE'      => 'c',
        'TWENTY_SIX' => 'z',
    ];
}

class TestClassEnumConst extends Enum
{
    const ONE        = 'a';
    const TWO        = 'a';
    const THREE      = 'a';
    const TWENTY_SIX = 'a';
}

class TestClassDuplicateEnum extends Enum
{
    protected static $enum = [
        'ONE'        => 'a',
        'TWO'        => 'b',
        'THREE'      => 'c',
        'TWENTY_SIX' => 'z',
    ];
}

class TestClassNumericEnum extends Enum
{
    protected static $enum = [
        'ONE'        => 1,
        'TWO'        => 2,
        'THREE'      => 3,
        'TWENTY_SIX' => 26,
    ];
}

class TestClassConflictEnumOne extends Enum
{
    const ONE = 'a';
}

class TestClassConflictEnumTwo extends Enum
{
    const TWO = 'b';
}

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
            'ONE', 'TWO', 'THREE', 'TWENTY_SIX',
        ], TestClassEnum::names());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testConstInstantiation()
    {
        $this->assertEquals([
            'ONE', 'TWO', 'THREE', 'TWENTY_SIX',
        ], TestClassEnumConst::names());
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
        ], TestClassConflictEnumOne::names());

        $this->assertEquals([
            'TWO',
        ], TestClassConflictEnumTwo::names());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCanInstantiate()
    {
        $enum = new TestClassEnum('a');

        $this->assertInstanceOf(Enum::class, $enum);
        $this->assertEquals('ONE', $enum->name());
        $this->assertEquals('a', $enum->value());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFailsInstantiation()
    {
        $this->expectException(UnexpectedValueException::class);

        $enum = new TestClassEnum('π');
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFriendlyValue()
    {
        $enum = TestClassEnum::TWENTY_SIX();

        $this->assertEquals('Twenty Six', $enum->friendlyValue());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCanMagicallyInstantiate()
    {
        $enum = TestClassEnum::ONE();

        $this->assertInstanceOf(Enum::class, $enum);
        $this->assertEquals('a', $enum->value());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCannotMagicallyInstantiateSomethingThatDoesntExist()
    {
        $this->expectException(BadMethodCallException::class);

        $enum = TestClassEnum::FOUR();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStringConversion()
    {
        $enum = TestClassEnum::ONE();

        $this->assertSame('a', (string) $enum);

        $enum = TestClassNumericEnum::THREE();

        $this->assertSame('3', (string) $enum);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEqualityComparison()
    {
        $enum = TestClassEnum::ONE();
        $other = TestClassEnum::ONE();

        $this->assertTrue($enum->is($other));
        $this->assertTrue($enum->equals($other));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNullEqualityComparison()
    {
        $enum = TestClassEnum::ONE();
        $other = null;

        $this->assertFalse($enum->is($other));
        $this->assertFalse($enum->equals($other));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEqualityComparisonFailsWhenSameClass()
    {
        $enum = TestClassEnum::ONE();
        $other = TestClassEnum::TWO();

        $this->assertFalse($enum->is($other));
        $this->assertFalse($enum->equals($other));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEqualityComparisonFailsWhenDifferentClassWithSameValue()
    {
        $enum = TestClassEnum::ONE();
        $other = TestClassDuplicateEnum::ONE();

        $this->assertFalse($enum->is($other));
        $this->assertFalse($enum->equals($other));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testInstantiationFromIdenticalEnum()
    {
        $enum = TestClassEnum::ONE();
        $other = new TestClassEnum($enum);

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
        $enum = TestClassEnum::ONE();
        $other = new TestClassDuplicateEnum($enum);

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
        $enum = TestClassEnum::ONE();

        $encoded = json_encode(['enum' => $enum]);

        $this->assertEquals('{"enum":"a"}', $encoded);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSerialisation()
    {
        $enum = TestClassEnum::ONE();
        $encoded = serialize($enum);

        $this->assertEquals('C:13:"TestClassEnum":3:{"a"}', $encoded);

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
        $enum = TestClassEnum::all();

        $this->assertTrue(TestClassEnum::ONE()->is($enum['ONE']));
        $this->assertTrue(TestClassEnum::TWO()->is($enum['TWO']));
        $this->assertTrue(TestClassEnum::THREE()->is($enum['THREE']));
        $this->assertTrue(TestClassEnum::TWENTY_SIX()->is($enum['TWENTY_SIX']));
    }


    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRandomAccessor()
    {
        $enum = TestClassEnum::random();

        $this->assertInstanceof(TestClassEnum::class, $enum);
    }
}
