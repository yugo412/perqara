<?php

declare(strict_types=1);

use App\Application\Actions\Machine\Vending\DeleteProductAction;
use App\Application\Actions\Machine\Vending\GetProductAction;
use App\Application\Actions\Machine\Vending\ListProductAction;
use App\Application\Actions\Machine\Vending\OrderProductAction;
use App\Application\Actions\Machine\Vending\StoreProductAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Views\Twig;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/doc', function (Request $request, Response $response): Response {
        $view = Twig::fromRequest($request);

        return $view->render($response, 'doc.html.twig');
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->group('/vending', function (Group $group): void {
        $group->get('', ListProductAction::class);
        $group->post('', StoreProductAction::class);
        $group->post('/order', OrderProductAction::class);
        $group->get('/product/{name}', GetProductAction::class);
        $group->delete('/product/{name}', DeleteProductAction::class);
    });
};
