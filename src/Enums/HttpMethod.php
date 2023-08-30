<?php

namespace TakeBlip\Enums;

enum HttpMethod: string
{
    case GET = 'GET';
    case PATCH = 'PATCH';
    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';
}
