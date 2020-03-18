<?php

namespace Tlr\PhpnumTests\Traits;

use BadMethodCallException;
use PHPUnit\Framework\TestCase;
use Tlr\PhpnumTests\Doubles\FooBar;
use Tlr\PhpnumTests\Doubles\SuperstitiousNumbers;

class CallsEnumerableMethodsTest extends TestCase
{
    /**
     * @test
     */
    public function resolvedMethodCall_toSingleWordMethod()
    {
        $enum = FooBar::FOO();
        $result = $enum->prepend('woop');

        $this->assertEquals('woop-foo', $result);
    }

    /**
     * @test
     */
    public function resolvedMethodCall_withBadMethod_throws()
    {
        $enum = FooBar::MONKEYS();
        $this->expectException(BadMethodCallException::class);
        $enum->monkeys('woop');
    }

    /**
     * @test
     */
    public function resolvedMethodCall_toMultiWordWordKeyInCamelCase()
    {
        $enum = SuperstitiousNumbers::KA_TET();
        $result = $enum->stringify();

        $this->assertEquals('1999', $result);
    }

    /**
     * @test
     */
    public function resolvedMethodCall_withInvalidMethodViaType_throws()
    {
        $enum = FooBar::MONKEYS();
        $this->expectException(BadMethodCallException::class);
        $enum->prepend('woop');
    }

    /**
     * @test
     */
    public function resolvedMethodCall_withConstName_handlesEdgeBugAndReturnsNewEnum()
    {
        $enum = FooBar::FOO();

        $other = $enum->MONKEYS();

        $this->assertFalse($enum === $other);
        $this->assertFalse($enum->is($other));
        $this->assertEquals('foo', $enum->value());
        $this->assertEquals('monkeys', $other->value());
    }
}
