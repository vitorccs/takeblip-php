<?php

namespace TakeBlip\Entities;

use TakeBlip\Enums\TemplateType;

class TemplateUrlDocument extends TemplateUrl
{
    /**
     * @param string $url
     * @param string $filename
     */
    public function __construct(string $url,
                                string $filename)
    {
        parent::__construct($url, TemplateType::DOCUMENT, $filename);
    }
}
