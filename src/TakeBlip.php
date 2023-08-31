<?php

namespace TakeBlip;

use TakeBlip\Exceptions\TakeBlipException;
use TakeBlip\Factories\MessageFactory;
use TakeBlip\Helpers\SanitizerHelper;
use TakeBlip\Http\Resource;
use TakeBlip\Entities\Template;

class TakeBlip extends Resource
{
    /**
     * @param string|null $id
     * @return object
     * @throws TakeBlipException
     */
    public function getMessageTemplates(string $id = null): object
    {
        return $this->post('commands', [
            'id' => $id,
            'to' => 'postmaster@wa.gw.msging.net',
            'method' => 'get',
            'uri' => '/message-templates'
        ]);
    }

    /**
     * @param string $cellPhone
     * @param string|null $id
     * @return object
     * @throws TakeBlipException
     */
    public function getUserIdentity(string $cellPhone, string $id = null): object
    {
        $numeric = SanitizerHelper::onlyNumeric($cellPhone);

        return $this->post('commands', [
            'id' => $id,
            'to' => 'postmaster@wa.gw.msging.net',
            'method' => 'get',
            'uri' => "lime://wa.gw.msging.net/accounts/+{$numeric}"
        ]);
    }

    /**
     * @param string $notificationId
     * @param string|null $id
     * @return object
     * @throws TakeBlipException
     */
    public function getNotificationEvents(string $notificationId, string $id = null): object
    {
        return $this->post('commands', [
            'id' => $id,
            'to' => 'postmaster@msging.net',
            'method' => 'get',
            'uri' => "/notifications?id={$notificationId}"
        ]);
    }

    /**
     * @param Template|array $template
     * @return null
     * @throws TakeBlipException
     */
    public function sendNotification($template)
    {
        $message = $template instanceof Template
            ? MessageFactory::create($template)
            : $template;

        return $this->post('messages', $message);
    }
}
