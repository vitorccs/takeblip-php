<?php

namespace TakeBlip\Factories;

use TakeBlip\Entities\Template;
use TakeBlip\Factories\Components\Link;
use TakeBlip\Factories\Components\QuickReply;
use TakeBlip\Factories\Components\Variable;

class MessageFactory
{
    /**
     * @param Template $template
     * @return array
     */
    public static function create(Template $template): array
    {
        return [
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
                    'components' => array_values(array_filter(array_merge(
                        [
                            static::createHeader($template),
                            static::createBody($template),
                        ],
                        static::addReplies($template)
                    )))
                ]
            ]
        ];
    }

    /**
     * @param Template $template
     * @return array
     */
    private static function createHeader(Template $template): array
    {
        $urls = static::addUrls($template);

        if (empty($urls)) return [];

        return [
            'type' => 'header',
            'parameters' => [
                $urls
            ]
        ];
    }

    /**
     * @param Template $template
     * @return array
     */
    private static function createBody(Template $template): array
    {
        $variables = static::addVariables($template);

        if (empty($variables)) return [];

        return [
            'type' => 'body',
            'parameters' => $variables
        ];
    }

    /**
     * @param Template $template
     * @return array
     */
    private static function addVariables(Template $template): array
    {
        return array_map(
            fn(string $variable) => (new Variable($variable))->export(),
            $template->variables
        );
    }

    /**
     * @param Template $template
     * @return array
     */
    private static function addReplies(Template $template): array
    {
        return array_map(
            fn(string $reply, int $index) => (new QuickReply($reply, $index))->export(),
            $template->replies,
            array_keys($template->replies)
        );
    }

    /**
     * @param Template $template
     * @return array
     */
    private static function addUrls(Template $template): array
    {
        if (is_null($template->url)) return [];

        return (new Link($template->url))->export();
    }
}
