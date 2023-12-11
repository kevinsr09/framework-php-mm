<?php

use App\Providers\RuleServiceProvider;
use Rumi\Providers\DatabaseServiceProvider;
use Rumi\Providers\ServerServiceProvider;
use Rumi\Providers\SessionStorageServiceProvider;
use Rumi\Providers\ViewServiceProvider;

return [
  'boot' => [
    DatabaseServiceProvider::class,
    ServerServiceProvider::class,
    SessionStorageServiceProvider::class,
    ViewServiceProvider::class,
  ],

  'runtime' => [
    RuleServiceProvider::class,
  ],


];