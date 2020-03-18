<?php

namespace Tlr\PhpnumTests\Doubles;

use Tlr\Phpnum\Enum;

/**
 * Class SuperstitiousNumbers
 *
 * @package Tlr\PhpnumTests\Doubles
 * @method static SuperstitiousNumbers CHINA()
 * @method static SuperstitiousNumbers LUCKY()
 * @method static SuperstitiousNumbers UNLUCKY()
 * @method static SuperstitiousNumbers KA_TET()
 * @method static SuperstitiousNumbers RHYTHM()
 * @method static SuperstitiousNumbers DEVIL()
 * @method static SuperstitiousNumbers BELPHAGOR_PRIME()
 *
 * @method string stringify()
 */
class SuperstitiousNumbers extends Enum
{
    const CHINA = 4;
    const LUCKY = 7;
    const UNLUCKY = 13;
    const KA_TET = 19;
    const RHYTHM = 23;
    const DEVIL = 666;
    const BELPHAGOR_PRIME = '1000000000000066600000000000001';

    public function stringifyForKaTet() : string
    {
        return "1999";
    }
}
