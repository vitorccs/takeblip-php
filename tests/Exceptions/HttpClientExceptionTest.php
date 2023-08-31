<?php

namespace TakeBlip\Test\Exceptions;

use GuzzleHttp\Psr7\Response;
use TakeBlip\Exceptions\HttpClientException;
use TakeBlip\Test\BaseTest;
use TakeBlip\Test\DataProviders\PhoneData;

class HttpClientExceptionTest extends BaseTest
{
    use PhoneData;

    /**
     * @dataProvider dataInvalidPhone
     */
    public function testFailureFromStatus(string $phone)
    {
        $this->expectException(HttpClientException::class);
        $this->expectExceptionCode(61);
        $this->expectExceptionMessage("The string supplied did not seem to be a phone number.");

        $this->handler->append(new Response(200, [], '{"status": "failure", "reason": { "code": 61, "description": "The string supplied did not seem to be a phone number."}}'));
        $this->takeBlip->getUserIdentity($phone);
    }

    /**
     * @dataProvider dataInvalidPhone
     */
    public function testFailureFromDescription(string $phone)
    {
        $this->expectException(HttpClientException::class);
        $this->expectExceptionCode(13);
        $this->expectExceptionMessage("Invalid authorization header");

        $this->handler->append(new Response(401, [], '{"code": 13, "description": "Invalid authorization header"}'));
        $this->takeBlip->getUserIdentity($phone);
    }
}
