<?php

namespace Rumi\Server;

use Rumi\Http\Request;
use Rumi\Http\Response;

interface Server{
  public function getRequest():Request;
  public function send_response(Response $response);
}
