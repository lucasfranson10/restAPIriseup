<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Factory\StreamFactory as Stream;
//use Psr\Http\Message\StreamFactoryInterface as Stream;
use Slim\App;

use App\Application\Models\Bootstrap;
use App\Application\Models\User;


return function (App $app) {
    $container = $app->getContainer(); 
    $app->addBodyParsingMiddleware();
    $path = $container->get('settings')['upload_directory'];
    Bootstrap::load($container);
    
    
    $app->get('/uploads/{string}', function (Request $request, Response $response, $args){
        
        $query = User::where('user_avatar', '/uploads/' . $args['string'])->get(); 
        
        if($query){
            return $response->withHeader('Content-Type', 'image/png')
            ->withBody((new Stream())->createStreamFromFile(__DIR__ .'/uploads/' . $args['string']));
        }       
    }); 
    

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
    $app->post('/user', function (Request $request, Response $response) use ($path){
        $directory = $path;
        $uploadedFiles = $request->getUploadedFiles();
        $avatar = $uploadedFiles['user_avatar'];
        
        if ($avatar->getError() === UPLOAD_ERR_OK ) {
            if(!empty($avatar)) $filename = moveUploadedFile($directory, $avatar);
        }
        
        $data = $request->getParsedBody();
        $user = User::create([
                                'user_avatar' => "/uploads/$filename",
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
    $app->put('/user/{num}', function (Request $request, Response $response, $args) use ($path) : Response {
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
        $user = User::find($args)->toArray();
        if(file_exists(__DIR__ .  $user[0]['user_avatar']))
            unlink(__DIR__ .  $user[0]['user_avatar']);
               
        $response->getbody()->write(json_encode(User::where('user_id', $args)->delete()));         
        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    });

    function moveUploadedFile($directory, $uploadedFile)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}

};

