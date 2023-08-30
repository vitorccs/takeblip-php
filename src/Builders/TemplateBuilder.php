<?php

namespace TakeBlip\Builders;

use TakeBlip\Entities\TemplateUrlDocument;
use TakeBlip\Entities\TemplateUrlImage;
use TakeBlip\Entities\Template;
use TakeBlip\Entities\TemplateUrlVideo;
use TakeBlip\Enums\TemplateType;
use TakeBlip\Exceptions\TakeBlipException;
use TakeBlip\Helpers\StringHelper;

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
        $this->template->variables[] = $variable;
        return $this;
    }

    /**
     * @param string $button
     * @return $this
     */
    public function addReply(string $button): self
    {
        $this->template->replies[] = $button;
        return $this;
    }

    /**
     * @param string $url
     * @param string $type
     * @param string|null $filename
     * @return $this
     * @throws TakeBlipException
     */
    public function setUrl(string  $url,
                           string  $type,
                           ?string $filename = null): self
    {
        $this->template->url = match ($type) {
            TemplateType::DOCUMENT->value => new TemplateUrlDocument($url, $filename),
            TemplateType::IMAGE->value => new TemplateUrlImage($url),
            TemplateType::VIDEO->value => new TemplateUrlVideo($url, $filename),
            default => throw new TakeBlipException('Invalid URL "type" value'),
        };

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
