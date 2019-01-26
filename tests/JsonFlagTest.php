<?php

use PHPUnit\Framework\TestCase;
use Tlr\Phpnum\JsonFlag;

class TestJsonFlagClass extends JsonFlag
{
    protected static $flags = [
        'A' => 'a',
        'B' => 'b',
        'C' => 'c',
        'D' => 'd',
        'E' => 'e',
    ];
}

class TestJsonFlagClassConsts extends JsonFlag
{
    const A             = 'a';
    const B             = 'b';
    const C             = 'c';
}

class JsonFlagTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testStaticInstantiation()
    {
        $this->assertEquals([
            'A'    => 'a',
            'B'    => 'b',
            'C'    => 'c',
            'D'    => 'd',
            'E'    => 'e',
        ], TestJsonFlagClass::values());
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testConstInstantiation()
    {
        $this->assertEquals([
            'A'    => 'a',
            'B'    => 'b',
            'C'    => 'c',
        ], TestJsonFlagClassConsts::values());
    }

}
