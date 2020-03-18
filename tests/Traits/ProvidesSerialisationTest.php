<?php

namespace Tlr\PhpnumTests\Traits;

use PHPUnit\Framework\TestCase;
use Tlr\PhpnumTests\Doubles\FooBar;

class ProvidesSerialisationTest extends TestCase
{
    /**
     * @test
     */
    public function jsonEncode_encodesValueToJson()
    {
        $enum = FooBar::MONKEYS();

        $encoded = json_encode(['enum' => $enum]);

        $this->assertEquals('{"enum":"monkeys"}', $encoded);
    }

    /**
     * @test
     */
    public function serialise_serialisesValue()
    {
        $enum = FooBar::MONKEYS();
        $encoded = serialize($enum);

        $this->assertEquals('C:30:"Tlr\PhpnumTests\Doubles\FooBar":9:{"monkeys"}', $encoded);

        $other = unserialize($encoded);

        $this->assertFalse($enum === $other);
        $this->assertTrue($enum->is($other));
    }
}
