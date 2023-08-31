<?php

namespace TakeBlip\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use PHPUnit\Framework\TestCase;
use TakeBlip\TakeBlip;

abstract class BaseTest extends TestCase
{
    protected MockHandler $handler;

    protected TakeBlip $takeBlip;

    public function setUp(): void
    {
        $this->handler = new MockHandler([]);

        $client = new Client([
            'handler' => HandlerStack::create($this->handler)
        ]);

        $this->takeBlip = new TakeBlip();
        $this->takeBlip->setClient($client);
    }
}
