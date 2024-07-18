<?php

declare(strict_types=1);

use App\Application\Middleware\SessionMiddleware;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

return function (App $app) {
    $app->add(SessionMiddleware::class);

    $twig = Twig::create(realpath(__DIR__ . '/../templates'), ['cache' => false]);
    $app->add(TwigMiddleware::create($app, $twig));
};
