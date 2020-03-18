<?php

namespace Tlr\PhpnumTests\Frameworks\Laravel;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tlr\Phpnum\Frameworks\Laravel\EnumServiceProvider;
use Tlr\PhpnumTests\Doubles\FooBar;

class ValidationTest extends TestCase
{
    /**
     * @test
     */
    public function ruleMacro_withEnum_resolvesToInRule()
    {
        (new EnumServiceProvider(null))->boot();

        $rule = Rule::enum(FooBar::class);

        $this->assertInstanceOf(In::class, $rule);
        $this->assertEquals('in:"foo","bar","baz","monkeys"', $rule->__toString());
    }

    /**
     * @test
     */
    public function ruleMacro_withInvalidEnum_throws()
    {
        (new EnumServiceProvider(null))->boot();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Value passed to Rule::enum must be instance of [Tlr\Phpnum\Contracts\Enum]. [Tlr\PhpnumTests\Frameworks\Laravel\ValidationTest] Given.');

        Rule::enum(static::class);
    }
}
