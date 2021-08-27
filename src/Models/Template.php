<?php

namespace TakeBlip\Models;

class Template
{
    /**
     * @var string
     */
    public string $id;

    /**
     * @var string
     */
    public string $identity;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var string
     */
    public string $namespace;

    /**
     * @var string
     */
    public string $languageCode = 'pt_BR';

    /**
     * @var array
     */
    public array $variables = [];

    /**
     * @var array
     */
    public array $replies = [];

    /**
     * @var TemplateUrl|null
     */
    public ?TemplateUrl $url = null;

    /**
     * @param string $id
     * @param string $identity
     * @param string $name
     * @param string $namespace
     */
    public function __construct(string $id,
                                string $identity,
                                string $name,
                                string $namespace)
    {
        $this->id = $id;
        $this->identity = $identity;
        $this->name = $name;
        $this->namespace = $namespace;
    }
}
