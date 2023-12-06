<?php

use Rumi\Session\Session;

function session(): Session{
  return app()->session;
}
