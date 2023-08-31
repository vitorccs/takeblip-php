<?php

namespace TakeBlip\Entities;

use TakeBlip\Enums\TemplateType;

class TemplateUrlImage extends TemplateUrl
{
    /**
     * @param string $url
     */
    public function __construct(string $url)
    {
        parent::__construct($url, TemplateType::IMAGE);
    }
}
