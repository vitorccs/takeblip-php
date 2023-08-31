<?php

namespace TakeBlip\Enums;

enum TemplateType: string
{
    case IMAGE = 'image';
    case DOCUMENT = 'document';
    case TEXT = 'text';
    case VIDEO = 'video';
}
