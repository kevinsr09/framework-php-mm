<?php

use App\Providers\RouteServiceProvider;
use App\Providers\RuleServiceProvider;
use Rumi\Providers\AuthServiveProvider;
use Rumi\Providers\CryptoServiceProvider;
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
    AuthServiveProvider::class,
  ],

  'runtime' => [
    RuleServiceProvider::class,
    RouteServiceProvider::class,
    CryptoServiceProvider::class,
  ],


];