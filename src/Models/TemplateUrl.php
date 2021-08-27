<?php

namespace TakeBlip\Models;

use TakeBlip\Helpers\UrlHelper;

class TemplateUrl
{
    /**
     * @var string
     */
    public string $url;

    /**
     * @var string
     */
    public string $type;

    /**
     * @var string|null
     */
    public ?string $filename;

    /**
     * @param string $url
     * @param string $type
     * @param string|null $filename
     */
    public function __construct(string $url,
                                string $type,
                                string $filename = null)
    {
        $this->url = $url;
        $this->type = $type;
        $this->filename = $filename;

        if (!UrlHelper::isValidType($type)) {
            throw new \InvalidArgumentException("\"$type\" is not a valid type");
        }

        if ($type === 'document' && empty($filename)) {
            throw new \InvalidArgumentException('"filename" argument is required when type is "document"');
        }
    }
}
