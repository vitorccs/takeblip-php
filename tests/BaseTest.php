<?php

namespace TakeBlip\Test;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use PHPUnit\Framework\TestCase;
use TakeBlip\Builders\TemplateBuilder;
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

    public function validPhone(): array
    {
        return [
            "valid_phone" => [getenv('VALID_PHONE')]
        ];
    }

    public function invalidPhone(): array
    {
        return [
            "invalid_phone" => [getenv('INVALID_PHONE')]
        ];
    }

    public function validTemplate(): array
    {
        $builder = new TemplateBuilder();
        $template = $builder
            ->create('user_identity',
                'my_template_name',
                'my_namespace_name')
            ->addVariable('MyVar1')
            ->addVariable('MyVar2')
            ->addReply('QuickReply1')
            ->addReply('QuickReply2')
            ->setUrl('https://www.domain.com/file.pdf', 'document', 'Your PDF file')
            ->get();

        return [
            "valid_template_message" => [$template]
        ];
    }
}
