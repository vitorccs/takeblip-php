<?php

namespace TakeBlip\Test;

use GuzzleHttp\Psr7\Response;
use TakeBlip\Entities\Template;
use TakeBlip\Test\DataProviders\PhoneData;
use TakeBlip\Test\DataProviders\WhatsAppTemplates;

class TakeBlipTest extends BaseTest
{
    use PhoneData, WhatsAppTemplates;

    public function testGetMessageTemplates()
    {
        $this->handler->append(new Response(200, [], '{"resource": null }'));

        $response = $this->takeBlip->getMessageTemplates();

        $this->assertIsObject($response);
        $this->assertObjectHasAttribute('resource', $response);
    }

    /**
     * @dataProvider dataValidPhone
     */
    public function testGetUserIdentity(string $phone)
    {
        $this->handler->append(new Response(200, [], '{"resource": null }'));

        $response = $this->takeBlip->getUserIdentity($phone);

        $this->assertIsObject($response);
        $this->assertObjectHasAttribute('resource', $response);
    }

    /**
     * @dataProvider dataValidPhone
     */
    public function testGetNotificationEvents(string $phone)
    {
        $this->handler->append(new Response(200, [], '{"resource": null }'));

        $response = $this->takeBlip->getNotificationEvents($phone);

        $this->assertIsObject($response);
        $this->assertObjectHasAttribute('resource', $response);
    }

    /**
     * @dataProvider dataValidTextTemplate
     */
    public function testSendNotification(Template $template)
    {
        $this->handler->append(new Response(200, [], ''));

        $response = $this->takeBlip->sendNotification($template);

        $this->assertNull($response);
    }
}
