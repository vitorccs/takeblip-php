<?php

namespace TakeBlip\Factories\Components;

use TakeBlip\Entities\TemplateUrl;

class Link
{
    /**
     * @var TemplateUrl
     */
    private TemplateUrl $url;

    /**
     * @param TemplateUrl $url
     */
    public function __construct(TemplateUrl $url)
    {

        $this->url = $url;
    }

    /**
     * @return array
     */
    public function export(): array
    {
        $details = [
            'link' => $this->url->url,
        ];

        if ($this->url->filename) {
            $details['filename'] = $this->url->filename;

        }

        return [
            'type' => $this->url->type->value,
            $this->url->type->value => $details
        ];
    }
}
