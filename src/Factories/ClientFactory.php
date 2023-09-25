<?php

namespace TakeBlip\Factories;

use GuzzleHttp\Client;
use TakeBlip\Exceptions\TakeBlipException;

class ClientFactory
{
    /**
     * API Key Parameter
     */
    const API_KEY_NAME = 'TAKEBLIP_API_KEY';

    /**
     * API Timeout Parameter
     */
    const API_TIMEOUT_NAME = 'TAKEBLIP_API_TIMEOUT';


    /**
     * API URL Parameter
     */
    const API_URL_NAME = 'TAKEBLIP_API_URL';

    /**
     * The API base URL
     */
    const DEFAULT_API_URL = 'https://msging.net';

    /**
     * Default API timeout
     */
    const DEFAULT_TIMEOUT = 20;

    /**
     * The SDK version number
     */
    const SDK_VERSION = '1.2.0';

    /**
     * @var string|null
     */
    private static ?string $apiKey;

    /**
     * @var int
     */
    private static int $timeout;

    /**
     * @var string|null
     */
    private static ?string $apiUrl;

    /**
     * @param string|null $apiKey
     * @param int|null $timeout
     * @param string|null $apiUrl
     * @return Client
     * @throws TakeBlipException
     */
    public static function create(?string $apiKey = null,
                                  ?int    $timeout = null,
                                  ?string $apiUrl = null): Client
    {
        self::$apiKey = $apiKey ?: getenv(self::API_KEY_NAME) ?: null;
        self::$timeout = $timeout ?: getenv(self::API_TIMEOUT_NAME) ?: self::DEFAULT_TIMEOUT;
        self::$apiUrl = $apiUrl ?: getenv(self::API_URL_NAME) ?: self::DEFAULT_API_URL;

        self::validate();

        return self::getClient();
    }

    /**
     * @throws TakeBlipException
     */
    private static function validate(): void
    {
        if (empty(self::$apiKey)) {
            throw new TakeBlipException(sprintf("%s parameter is required", self::API_KEY_NAME));
        }

        if (!is_numeric(self::$timeout)) {
            throw new TakeBlipException(sprintf("%s is not numeric", self::API_TIMEOUT_NAME));
        }
    }

    /**
     * @return Client
     */
    private static function getClient(): Client
    {
        return new Client([
            'base_uri' => self::$apiUrl,
            'timeout' => self::$timeout,
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Key ' . self::$apiKey,
                'User-Agent' => 'TakeBlip-PHP/' . self::SDK_VERSION
            ]
        ]);
    }
}
