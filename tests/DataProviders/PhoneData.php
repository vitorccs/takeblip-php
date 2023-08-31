<?php

namespace TakeBlip\Test\DataProviders;

trait PhoneData
{
    public static function dataValidPhone(): array
    {
        return [
            'valid_phone' => [getenv('VALID_PHONE')]
        ];
    }

    public static function dataInvalidPhone(): array
    {
        return [
            'invalid_phone' => [getenv('INVALID_PHONE')]
        ];
    }
}
