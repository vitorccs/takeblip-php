<?php

namespace TakeBlip\Exceptions;

abstract class HttpException extends TakeBlipException
{
    /**
     * @var int|null
     */
    private ?int $httpCode;

    /**
     * @param string $message
     * @param int|null $httpCode
     * @param int $code
     */
    public function __construct($message = "", ?int $httpCode = null, $code = 0)
    {
        parent::__construct($message, $code);

        $this->httpCode = $httpCode;
    }

    /**
     * @return int|null
     */
    public function getHttpCode(): ?int
    {
        return $this->httpCode;
    }
}
