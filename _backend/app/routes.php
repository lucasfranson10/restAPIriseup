<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

use App\Application\Models\Bootstrap;
use App\Application\Models\User;


return function (App $app) {
    $container = $app->getContainer();    
    Bootstrap::load($container);
    
    

    //index
    $app->get('/user', function (Request $request, Response $response){
        $response->getbody()->write(json_encode(User::all()->toArray()));         
        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    }); 

    //show
    $app->get('/user/{num}', function (Request $request, Response $response, $args){
        $response->getbody()->write(json_encode(User::find($args)->toArray()));         
        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    }); 

    //store
    $app->post('/user', function (Request $request, Response $response){
        User::create();
        $response->getbody()->write(json_encode(User::all()->toArray()));         
        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    }); 

    //update
    $app->put('/user/{num}', function (Request $request, Response $response,$args){
        $response->getbody()->write(json_encode(User::all()->toArray()));         
        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    }); 

    //delete
    $app->delete('/user/{num}/delete', function (Request $request, Response $response){
        $response->getbody()->write(json_encode(User::all()->toArray()));         
        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    });

};



/**
 *  $app->get('/user', function (Request $request, Response $response){
        $response->getbody()->write(json_encode(User::all()->toArray()));         
        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    });
    
     User::create();
        $response->getbody()->write(json_encode(User::all()->toArray()));         
        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
 */