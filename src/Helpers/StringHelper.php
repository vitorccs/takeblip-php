<?php

namespace TakeBlip\Helpers;

use Ramsey\Uuid\Uuid;

class StringHelper
{
    public static function uuid4(): string
    {
        return Uuid::uuid4()->toString();
    }
}