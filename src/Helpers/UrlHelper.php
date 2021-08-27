<?php

namespace TakeBlip\Helpers;

class UrlHelper
{
    /**
     *
     */
    const TYPES = [
        'image',
        'document',
        'video'
    ];

    public static function isValidType(string $type): bool
    {
        return in_array($type, self::TYPES);
    }
}
