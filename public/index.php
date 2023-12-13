<?php

require_once '../vendor/autoload.php';

use Rumi\App;

$app = App::bootstrap(dirname(__DIR__))->run();
