<?php

namespace TakeBlip\Test\Factories;

use TakeBlip\Factories\MessageFactory;
use TakeBlip\Models\Template;
use TakeBlip\Test\BaseTest;

class MessageFactoryTest extends BaseTest
{
    /**
     * @dataProvider validTemplate
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
     * @dataProvider validTemplate
     */
    public function testVariables(Template $template)
    {
        $message = MessageFactory::create($template);

        $variables = array_values(array_filter($message['content']['template']['components'], function ($component) {
            return $component['type'] === 'body';
        }));

        $this->assertCount(1, $variables);
        $this->assertArrayHasKey('parameters', $variables[0]);
        $this->assertCount(count($template->variables), $variables[0]['parameters']);

        foreach($variables[0]['parameters'] as $parameter) {
            $this->assertArrayHasKey('type', $parameter);
            $this->assertArrayHasKey('text', $parameter);
        }
    }

    /**
     * @dataProvider validTemplate
     */
    public function testReplies(Template $template)
    {
        $message = MessageFactory::create($template);

        $replies = array_values(array_filter($message['content']['template']['components'], function ($component) {
            return $component['type'] === 'button' &&
                $component['sub_type'] === 'quick_reply' &&
                is_numeric($component['index'] ?? null);
        }));

        $this->assertCount(count($template->replies), $replies);
        $this->assertArrayHasKey('parameters', $replies[0]);
        $this->assertCount(1, $replies[0]['parameters']);
        $this->assertArrayHasKey('type', $replies[0]['parameters'][0]);
        $this->assertArrayHasKey('payload', $replies[0]['parameters'][0]);
    }

    /**
     * @dataProvider validTemplate
     */
    public function testUrls(Template $template)
    {
        $message = MessageFactory::create($template);

        $urls = array_values(array_filter($message['content']['template']['components'], function ($component) {
            return $component['type'] === 'header';
        }));

        $countTemplateUrl = is_null($template->url) ? 0 : 1;

        $this->assertCount($countTemplateUrl, $urls);

        if ($countTemplateUrl > 0) {
            $this->assertArrayHasKey('parameters', $urls[0]);
            $this->assertCount(1, $urls[0]['parameters']);
            $this->assertArrayHasKey('type', $urls[0]['parameters'][0]);

            $type = $urls[0]['parameters'][0]['type'];
            $this->assertArrayHasKey($type, $urls[0]['parameters'][0]);
            $this->assertArrayHasKey('link', $urls[0]['parameters'][0][$type]);
            $this->assertArrayHasKey('filename', $urls[0]['parameters'][0][$type]);
        }
    }
}
