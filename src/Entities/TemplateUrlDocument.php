<?php

namespace TakeBlip\Entities;

use TakeBlip\Enums\TemplateType;

class TemplateUrlDocument extends TemplateUrl
{
    /**
     * @param string $url
     * @param string|null $filename
     */
    public function __construct(string $url,
                                ?string $filename = null)
    {
        $filename = $filename ?: basename($url);

        parent::__construct($url, TemplateType::DOCUMENT, $filename);
    }
}
