<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

use App\Application\Models\Bootstrap;
use App\Application\Models\User;


return function (App $app) {
    $container = $app->getContainer();    
    Bootstrap::load($container);
    $app->addBodyParsingMiddleware();
    
    

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
        $data = $request->getParsedBody();
        $user = User::create([
            'user_name' => $data['user_name'],
            'user_email' => $data['user_email'],
            'user_prof' => $data['user_prof'],
            'user_exp' => $data['user_exp'],
            'user_phone' => $data['user_phone'],
            'user_loc' => $data['user_loc'],    
        ]);
        
        !$user ? $response->getbody()->write("0") : $response->getbody()->write("1");

        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
       
    }); 

    //update
    $app->put('/user/{num}', function (Request $request, Response $response, $args) : Response {
        $data = $request->getParsedBody();        

        $user = User::where('user_id', $args)->update([
            'user_name' => $data['user_name'],
            'user_email' => $data['user_email'],
            'user_prof' => $data['user_prof'],
            'user_exp' => $data['user_exp'],
            'user_phone' => $data['user_phone'],
            'user_loc' => $data['user_loc'],    
        ]);

        !$user ? $response->getbody()->write("0") : $response->getbody()->write("1");
       
        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    }); 

    //delete
    $app->delete('/user/{num}/delete', function (Request $request, Response $response,$args){
        $response->getbody()->write(json_encode(User::where('user_id', $args)->delete()));         
        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    });

};

