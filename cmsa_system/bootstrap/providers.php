<?php

return [
    App\Providers\AppServiceProvider::class,
    Spatie\Html\HtmlServiceProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
  App\Http\Middleware\ForceHttpToHttps::class,
];
