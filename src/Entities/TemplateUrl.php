<?php

namespace TakeBlip\Entities;

use TakeBlip\Enums\TemplateType;

abstract class TemplateUrl
{
    /**
     * @var string
     */
    public string $url;

    /**
     * @var TemplateType
     */
    public TemplateType $type;

    /**
     * @var string|null
     */
    public ?string $filename;

    /**
     * @param string $url
     * @param TemplateType $type
     * @param string|null $filename
     */
    public function __construct(string       $url,
                                TemplateType $type,
                                ?string      $filename = null)
    {
        $this->url = $url;
        $this->type = $type;
        $this->filename = $filename;
    }
}
