<?php
declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UploadedFileFactoryInterface as UploadedFile;
use Slim\App;

use App\Application\Models\Bootstrap;
use App\Application\Models\User;


return function (App $app) {
    $container = $app->getContainer(); 
    $path = $container->get('settings')['upload_directory'];
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
    $app->post('/user', function (Request $request, Response $response) use ($path){

        $directory = $path;
        $filename = NULL;
        $uploadedFiles = $request->getUploadedFiles();

        if($uploadedFiles){
            $avatar = $uploadedFiles['user_avatar'];


            if ($avatar->getError() === UPLOAD_ERR_OK) {
                $filename = moveUploadedFile($directory, $avatar);
            }
    
        }

        $data = $request->getParsedBody();
        $user = User::create([
            'user_avatar' => "$directory/$filename",
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

    function moveUploadedFile($directory, $uploadedFile)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
    $filename = sprintf('%s.%0.8s', $basename, $extension);

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

    return $filename;
}

};

