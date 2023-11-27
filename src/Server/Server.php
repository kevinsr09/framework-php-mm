<?php

namespace Rumi\Server;

use Rumi\Http\HttpMethod;

interface Server{
  public function request_uri():string;
  public function request_method():HttpMethod;
  public function post_data():array;
  public function query_params():array;
}
