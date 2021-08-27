<?php

namespace TakeBlip\Helpers;

class SanitizerHelper
{
    /**
     * Remove non-numeric chars
     *
     * @param string|int $value
     * @return string
     */
    public static function onlyNumeric($value): string
    {
        return preg_replace("/[^0-9]/", '', (string)$value);
    }
}
