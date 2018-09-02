<?php

$botman = resolve('botman');

$botman->middleware->received(new \App\Http\Middleware\LoadUserMiddleware());

$botman->hears('/start', \App\Http\Controllers\Users\StoreController::class);

$botman->hears('/sites', \App\Http\Controllers\Sites\IndexController::class);
$botman->hears('/newsite (.*[^\s])', \App\Http\Controllers\Sites\CreateController::class);
$botman->hears('/site (.*[^\s])', \App\Http\Controllers\Sites\ShowController::class);
$botman->hears('/deletesite (.*[^\s])', \App\Http\Controllers\Sites\DestroyController::class);
