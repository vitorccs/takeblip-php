<?php

namespace TakeBlip\Test\Models;

use TakeBlip\Models\TemplateUrl;
use TakeBlip\Test\BaseTest;

class TemplateUrlTest extends BaseTest
{
    public function testInvalidType()
    {
        $this->expectException(\InvalidArgumentException::class);
        new TemplateUrl("https://www.domain.com", "INVALID");
    }

    public function testFilenameRequired()
    {
        $this->expectException(\InvalidArgumentException::class);
        new TemplateUrl("https://www.domain.com", "document");
    }
}
