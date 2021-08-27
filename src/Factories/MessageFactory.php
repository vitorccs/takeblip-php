<?php

namespace TakeBlip\Factories;

use TakeBlip\Models\Template;

class MessageFactory
{
    /**
     * @param Template $template
     * @return array
     */
    public static function create(Template $template): array
    {
        $message = [
            'id' => $template->id,
            'to' => $template->identity,
            'type' => 'application/json',
            'content' => [
                'type' => 'template',
                'template' => [
                    'namespace' => $template->namespace,
                    'name' => $template->name,
                    'language' => [
                        'code' => $template->languageCode,
                        'policy' => 'deterministic'
                    ],
                    'components' => []
                ]
            ]
        ];

        self::addUrls($template, $message);
        self::addVariables($template, $message);
        self::addReplies($template, $message);

        return $message;
    }

    /**
     * @param Template $template
     * @param array $message
     */
    private static function addVariables(Template $template, array &$message): void
    {
        $component = [
            'type' => 'body',
            'parameters' => array_map(function (string $variable) {
                return [
                    'type' => 'text',
                    'text' => $variable
                ];
            }, $template->variables)
        ];

        array_push($message['content']['template']['components'], $component);
    }

    /**
     * @param Template $template
     * @param array $message
     */
    private static function addReplies(Template $template, array &$message): void
    {
        foreach ($template->replies as $i => $reply) {
            $component = [
                'type' => 'button',
                'sub_type' => 'quick_reply',
                'index' => $i,
                'parameters' => [
                    [
                        'type' => 'payload',
                        'payload' => $reply
                    ]
                ]
            ];

            array_push($message['content']['template']['components'], $component);
        }
    }

    /**
     * @param Template $template
     * @param array $message
     */
    private static function addUrls(Template $template, array &$message): void
    {
        if (is_null($template->url)) return;

        $component = [
            'type' => 'header',
            'parameters' => [
                [
                    'type' => $template->url->type,
                    $template->url->type => [
                        'filename' => $template->url->filename,
                        'link' => $template->url->url
                    ]
                ]
            ]
        ];

        array_push($message['content']['template']['components'], $component);
    }
}
