<?php

namespace Rumi\Http;

enum HttpMethod: string
{
  case GET = 'GET';
  case POST = 'POST';
  case PUT = 'PUT';
  case PATCH = 'PATCH';
  case DELETE = 'DELETE';
  case OPTIONS = 'OPTIONS';
  case HEAD = 'HEAD';
}
