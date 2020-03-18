<?php

namespace Tlr\PhpnumTests\Frameworks\Laravel;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;
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
        (new EnumServiceProvider(null))->applyRuleMacros();

        $rule = Rule::enum(FooBar::class);

        $this->assertInstanceOf(In::class, $rule);
        $this->assertEquals('in:"foo","bar","baz","monkeys"', $rule->__toString());
    }
}
