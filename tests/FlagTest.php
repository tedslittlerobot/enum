<?php

use PHPUnit\Framework\TestCase;
use Tlr\Phpnum\Flag;

class TestFlagClass extends Flag
{
    protected static $zero = 'ZERO';
    protected static $flags = ['A', 'B', 'C', 'D', 'E'];
}

class TestFlagClassNoZero extends Flag
{
    protected static $flags = ['A', 'B'];
}

class TestFlagClassStupidConsts extends Flag
{
    const ZERO          = 0;
    const A             = 1;
    const B             = 2;
    const NO_GOOD_VALUE = 3;
}

class TestFlagClassMissingFlags extends Flag
{
    const ZERO          = 0;
    const A             = 1;
    const B             = 2;
    // const C             = 4; // Missing 0b0100
    const D             = 8;
}

class FlagTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStaticInstantiation()
    {
        $this->assertEquals([
            'ZERO' => 0,
            'A'    => 1,
            'B'    => 2,
            'C'    => 4,
            'D'    => 8,
            'E'    => 16,
        ], TestFlagClass::values());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNonZeroStaticInstantiation()
    {
        $this->assertEquals([
            'A' => 1,
            'B' => 2,
        ], TestFlagClassNoZero::values());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testConstInstantiation()
    {
        $this->assertEquals([
            'ZERO'          => 0,
            'A'             => 1,
            'B'             => 2,
            'NO_GOOD_VALUE' => 3,
        ], TestFlagClassStupidConsts::values());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCombineFlags()
    {
        $this->assertEquals(
            3,
            TestFlagClass::combineFlags([
                TestFlagClass::A(),
                TestFlagClass::B(),
            ])->value()
        );
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUnionFlags()
    {
        $this->assertEquals(
            3,
            TestFlagClass::union(
                TestFlagClass::A(),
                TestFlagClass::B()
            )->value()
        );

        $this->assertEquals(
            0,
            TestFlagClass::union()->value()
        );
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCombineFlagsFailsWithOtherFlagTypes()
    {
        $this->expectException(UnexpectedValueException::class);

        TestFlagClass::combineFlags([
            TestFlagClass::A(),
            TestFlagClassNoZero::B(),
        ]);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testInstantiation()
    {
        $this->assertSame(
            2,
            TestFlagClass::B()->value()
        );
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testInstantiationOfMixedFlagValues()
    {
        $this->assertSame(
            3,
            (new TestFlagClass(3))->value()
        );

        $this->assertSame(
            31,
            (new TestFlagClass(31))->value()
        );
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testInstantiationOfMixedFlagValuesErrorsOutOfBounds()
    {
        $this->expectException(UnexpectedValueException::class);

        new TestFlagClass(32);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicFlagMatches()
    {
        $comparator = TestFlagClass::B();

        $this->assertFalse($comparator->matches(TestFlagClass::A()));
        $this->assertTrue($comparator->matches(TestFlagClass::B()));
        $this->assertFalse($comparator->matches(TestFlagClass::C()));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCompoundFlagMatches()
    {
        $this->assertTrue(TestFlagClass::B()->matches(
            TestFlagClass::A(),
            TestFlagClass::B(),
            TestFlagClass::C()
        ));

        $this->assertFalse(TestFlagClass::B()->matches(
            TestFlagClass::A(),
            TestFlagClass::C()
        ));

        $this->assertTrue((new TestFlagClass(0b0111))->matches(
            TestFlagClass::A(),
            TestFlagClass::B(),
            TestFlagClass::C()
        ));

        $this->assertTrue((new TestFlagClass(0b1010))->matches(
            TestFlagClass::A(),
            TestFlagClass::B()
        ));

        $this->assertFalse((new TestFlagClass(0b1010))->matches(
            TestFlagClass::A(),
            TestFlagClass::C()
        ));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStrictFlagMatches()
    {
        $this->assertTrue(TestFlagClass::B()->matchesAll(
            TestFlagClass::B()
        ));

        $this->assertFalse(TestFlagClass::B()->matchesAll(
            TestFlagClass::A(),
            TestFlagClass::B(),
            TestFlagClass::C()
        ));

        $this->assertTrue((new TestFlagClass(0b0111))->matchesAll(
            TestFlagClass::A(),
            TestFlagClass::B(),
            TestFlagClass::C()
        ));

        $this->assertFalse((new TestFlagClass(0b0111))->matchesAll(
            TestFlagClass::A(),
            TestFlagClass::B(),
            TestFlagClass::C(),
            TestFlagClass::D()
        ));

        $this->assertTrue((new TestFlagClass(0b1010))->matchesAll(
            TestFlagClass::B(),
            TestFlagClass::D()
        ));

        $this->assertFalse((new TestFlagClass(0b1010))->matchesAll(
            TestFlagClass::B(),
            TestFlagClass::C(),
            TestFlagClass::D()
        ));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetMatchedFlagsFromSingleFlag()
    {
        $comparator = TestFlagClass::B()->matchedFlags();

        $this->assertCount(1, $comparator);
        $this->assertSame(2, $comparator[0]->value());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetMatchedFlagsFromMixedFlag()
    {
        $comparator = (new TestFlagClass(0b1010))->matchedFlags();

        $this->assertCount(2, $comparator);
        $this->assertSame(2, $comparator[0]->value());
        $this->assertSame(8, $comparator[1]->value());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetCompoundFlagForAll()
    {
        $comparator = TestFlagClass::flagForAll();

        $this->assertSame(0b11111, $comparator->value());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetCompoundFlagForAllWithMissing()
    {
        $comparator = TestFlagClassMissingFlags::flagForAll();

        $this->assertSame(0b1011, $comparator->value());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCompoundFlagValidationFailsForMissingFlag()
    {
        $this->assertTrue(TestFlagClassMissingFlags::isValidValue(1));
        $this->assertTrue(TestFlagClassMissingFlags::isValidValue(2));
        $this->assertFalse(TestFlagClassMissingFlags::isValidValue(4));
        $this->assertTrue(TestFlagClassMissingFlags::isValidValue(8));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFlagValueSplit()
    {
        $values = TestFlagClass::union(TestFlagClass::A(), TestFlagClass::B())->split();

        $this->assertCount(2, $values);
        $this->assertTrue(reset($values)->is(TestFlagClass::A()));
        $this->assertTrue(end($values)->is(TestFlagClass::B()));
    }
}
