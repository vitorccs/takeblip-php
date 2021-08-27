<?php

namespace TakeBlip\Test;

use GuzzleHttp\Psr7\Response;
use TakeBlip\Models\Template;

class TakeBlipTest extends BaseTest
{
    public function testGetMessageTemplates()
    {
        $this->handler->append(new Response(200, [], '{"resource": null }'));

        $response = $this->takeBlip->getMessageTemplates();

        $this->assertIsObject($response);
        $this->assertObjectHasAttribute('resource', $response);
    }

    /**
     * @dataProvider validPhone
     */
    public function testGetUserIdentity(string $phone)
    {
        $this->handler->append(new Response(200, [], '{"resource": null }'));

        $response = $this->takeBlip->getUserIdentity($phone);

        $this->assertIsObject($response);
        $this->assertObjectHasAttribute('resource', $response);
    }

    /**
     * @dataProvider validPhone
     */
    public function testGetNotificationEvents(string $phone)
    {
        $this->handler->append(new Response(200, [], '{"resource": null }'));

        $response = $this->takeBlip->getNotificationEvents($phone);

        $this->assertIsObject($response);
        $this->assertObjectHasAttribute('resource', $response);
    }

    /**
     * @dataProvider validTemplate
     */
    public function testSendNotification(Template $template)
    {
        $this->handler->append(new Response(200, [], ''));

        $response = $this->takeBlip->sendNotification($template);

        $this->assertNull($response);
    }
}
