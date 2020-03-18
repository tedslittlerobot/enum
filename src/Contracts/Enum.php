<?php

namespace Tlr\Phpnum\Contracts;

use JsonSerializable;
use Serializable;

interface Enum extends
    EnumAccessors,
    EnumComparisons,
    EnumValueProvider,
    JsonSerializable,
    Serializable
{
    //
}
