<?php

use Controllers\Routes\Render;
use Tnapf\Router\Exceptions\HttpNotFound;
use Tnapf\Router\Router;

Router::get("/", Render::class)
    ->addStaticArgument("path", "home")
;

Router::catch(HttpNotFound::class, Render::class)
    ->addStaticArgument("path", "404")
    ->addStaticArgument("context", ["uri" => $_SERVER['REQUEST_URI']])
    ->addStaticArgument("status", 404)
;
