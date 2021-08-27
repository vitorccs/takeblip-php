<?php

namespace TakeBlip\Builders;

use TakeBlip\Helpers\StringHelper;
use TakeBlip\Models\Template;
use TakeBlip\Models\TemplateUrl;

class TemplateBuilder
{
    /**
     * @var Template
     */
    protected Template $template;

    /**
     * @param string $name
     * @param string $namespace
     * @param string $identity
     * @return $this
     */
    public function create(string $identity,
                           string $name,
                           string $namespace): self
    {
        $this->template = new Template(
            StringHelper::uuid4(),
            $identity,
            $name,
            $namespace
        );
        return $this;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId(string $id): self
    {
        $this->template->id = $id;
        return $this;
    }

    /**
     * @param string $languageCode
     * @return $this
     */
    public function setLanguageCode(string $languageCode): self
    {
        $this->template->languageCode = $languageCode;
        return $this;
    }

    /**
     * @param string $variable
     * @return $this
     */
    public function addVariable(string $variable): self
    {
        array_push($this->template->variables, $variable);
        return $this;
    }

    /**
     * @param string $button
     * @return $this
     */
    public function addReply(string $button): self
    {
        array_push($this->template->replies, $button);
        return $this;
    }

    /**
     * @param string $url
     * @param string $type
     * @param string|null $filename
     * @return $this
     */
    public function setUrl(string $url,
                           string $type,
                           ?string $filename = null): self
    {
        $this->template->url = new TemplateUrl($url, $type, $filename);
        return $this;
    }

    /**
     * @return Template
     */
    public function get(): Template
    {
        return $this->template;
    }
}
