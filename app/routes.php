<?php
declare(strict_types=1);

//use App\Application\Actions\User\ListUsersAction;
//use App\Application\Actions\User\ViewUserAction;
//use App\Application\Actions\HomeAction;
//use Psr\Http\Message\ResponseInterface as Response;
//use Psr\Http\Message\ServerRequestInterface as Request;
//use Slim\Interfaces\RouteCollectorProxyInterface as Group;
//use App\Application\Actions\User\UserCreateAction;

use Slim\App;
use App\Application\Actions\Url\UrlsListAction;
use App\Application\Actions\Url\UrlRedirectAction;
use App\Application\Actions\Url\UrlCreateAction;
use App\Application\Actions\Url\UrlCheckAction;

return function (App $app) {
//    $app->options('/{routes:.*}', function (Request $request, Response $response) {
//        // CORS Pre-Flight OPTIONS Request Handler
//        return $response;
//    });

//    $app->get('/hello/{name}', function (Request $request, Response $response)
//    {
//        $name = $request->getAttribute('name');
//        $response->getBody()->write("Hello, $name");
//        return $response;
//    });
//    $app->get('/', HomeAction::class)->setName('home');;
//    $app->get('/', function (Request $request, Response $response) {
//        $response->getBody()->write('Hello world!');
//        return $response;
//    });
//    $app->group('/users', function (Group $group) {
//        $group->get('', ListUsersAction::class);
//        $group->get('/{id}', ViewUserAction::class);
//    });
//    $app->get('/users', UserCreateAction::class.':list_users');
//    $app->get('/check_users_db', UserCreateAction::class);


    $app->get('/redirect/{shortUrl}', UrlRedirectAction::class.':redirect_url');
    $app->get('/urls', UrlsListAction::class.':list_urls');
    $app->post('/url', UrlCreateAction::class.':new_url');
    $app->get('/url/{id}', UrlCheckAction::class.':check_url');
//    $app->put('/url/{id}', UrlAction::class.':modify_url'); Not Necessary
    $app->get('/check_urls_db', UrlCheckAction::class);

};
