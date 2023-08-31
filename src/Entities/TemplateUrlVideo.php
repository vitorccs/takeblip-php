<?php

namespace TakeBlip\Entities;

use TakeBlip\Enums\TemplateType;

class TemplateUrlVideo extends TemplateUrl
{
    /**
     * @param string $url
     * @param string $filename
     */
    public function __construct(string $url,
                                string $filename)
    {
        parent::__construct($url, TemplateType::VIDEO, $filename);
    }
}
