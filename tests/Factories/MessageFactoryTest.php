<?php

namespace TakeBlip\Test\Factories;

use TakeBlip\Entities\Template;
use TakeBlip\Factories\MessageFactory;
use TakeBlip\Test\BaseTest;

class MessageFactoryTest extends BaseTest
{
    /**
     * @dataProvider validTextTemplate
     * @dataProvider validDocumentTemplate
     * @dataProvider validImageTemplate
     * @dataProvider validVideoTemplate
     */
    public function testBase(Template $template)
    {
        $message = MessageFactory::create($template);

        $this->assertArrayHasKey('id', $message);
        $this->assertSame($message['id'], $template->id);

        $this->assertArrayHasKey('to', $message);
        $this->assertSame($message['to'], $template->identity);

        $this->assertArrayHasKey('content', $message);
        $this->assertArrayHasKey('template', $message['content']);

        $this->assertArrayHasKey('namespace', $message['content']['template']);
        $this->assertSame($message['content']['template']['namespace'], $template->namespace);

        $this->assertArrayHasKey('name', $message['content']['template']);
        $this->assertSame($message['content']['template']['name'], $template->name);

        $this->assertArrayHasKey('language', $message['content']['template']);
        $this->assertArrayHasKey('code', $message['content']['template']['language']);
        $this->assertSame($message['content']['template']['language']['code'], $template->languageCode);

        $this->assertArrayHasKey('components', $message['content']['template']);
    }

    /**
     * @dataProvider validTextTemplate
     * @dataProvider validDocumentTemplate
     * @dataProvider validImageTemplate
     * @dataProvider validVideoTemplate
     */
    public function testHeaderSection(Template $template)
    {
        $message = MessageFactory::create($template);

        $headers = array_values(
            array_filter(
                $message['content']['template']['components'],
                fn($component) => ($component['type'] ?? null) === 'header'
            )
        );

        $isHeaderRequired = !is_null($template->url);

        $this->assertCount($isHeaderRequired ? 1 : 0, $headers);

        if ($isHeaderRequired) {
            $this->assertUrls($template, reset($headers));
        }
    }

    /**
     * @dataProvider validTextTemplate
     * @dataProvider validDocumentTemplate
     * @dataProvider validImageTemplate
     * @dataProvider validVideoTemplate
     */
    public function testBodySection(Template $template)
    {
        $message = MessageFactory::create($template);

        $bodies = array_values(
            array_filter(
                $message['content']['template']['components'],
                fn($component) => $component['type'] === 'body'
            )
        );

        $isBodyRequired = count($template->variables);

        $this->assertCount($isBodyRequired ? 1 : 0, $bodies);

        if ($isBodyRequired) {
            $this->assertVariables($template, reset($bodies));
        }
    }

    /**
     * @dataProvider validTextTemplate
     * @dataProvider validDocumentTemplate
     * @dataProvider validImageTemplate
     * @dataProvider validVideoTemplate
     */
    public function testRepliesSection(Template $template)
    {
        $message = MessageFactory::create($template);

        $replies = array_values(
            array_filter($message['content']['template']['components'],
                function ($component) {
                    return ($component['type'] ?? null) === 'button' &&
                        ($component['sub_type'] ?? null) === 'quick_reply';
                }
            )
        );

        $this->assertCount(count($template->replies), $replies);

        foreach ($replies as $i => $reply) {
            $this->assertArrayHasKey('index', $reply);
            $this->assertArrayHasKey('parameters', $reply);

            $this->assertSame($i, $reply['index']);
            $this->assertCount(1, $reply['parameters']);

            $this->assertArrayHasKey('type', $reply['parameters'][0]);
            $this->assertArrayHasKey('payload', $reply['parameters'][0]);

            $this->assertSame($template->replies[$i], $reply['parameters'][0]['payload']);
        }
    }

    /**
     * @param Template $template
     * @param array $header
     * @return void
     */
    private function assertUrls(Template $template, array $header): void
    {
        $this->assertArrayHasKey('parameters', $header);

        $urls = array_values(
            array_filter(
                $header['parameters'],
                fn($parameter) => in_array(($parameter['type'] ?? null), ['document', 'image', 'video'])
            )
        );

        $this->assertCount(1, $urls);

        foreach ($urls as $url) {
            $type = $template->url->type->value;

            $this->assertSame($type, $url['type']);
            $this->assertArrayHasKey($type, $url);

            $this->assertArrayHasKey('link', $url[$type]);
            $this->assertSame($template->url->url, $url[$type]['link']);

            if (!is_null($template->url->filename)) {
                $this->assertArrayHasKey('filename', $url[$type]);
                $this->assertSame($template->url->filename, $url[$type]['filename']);
            } else {
                $this->assertArrayNotHasKey('filename', $url[$type]);
            }
        }
    }

    /**
     * @param Template $template
     * @param array $body
     * @return void
     */
    private function assertVariables(Template $template, array $body): void
    {
        $this->assertArrayHasKey('parameters', $body);

        $variables = array_values(
            array_filter(
                $body['parameters'],
                fn($parameter) => ($parameter['type'] ?? null) === 'text'
            )
        );

        $this->assertCount(count($template->variables), $variables);

        foreach ($variables as $i => $variable) {
            $this->assertArrayHasKey('text', $variable);
            $this->assertSame($template->variables[$i], $variable['text']);
        }
    }
}
