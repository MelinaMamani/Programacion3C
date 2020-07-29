<?php

namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ValidarDatosMiddleware {
    public function __invoke(Request $request, RequestHandler $handler): Response
        {
            
            $response = new Response();

            $body =$request->getParsedBody();
            
            if (isset($body['email']) && isset($body['clave']) && isset($body['tipo']) && isset($body['nombre'])) {
                if($body['email'] == "" || $body['clave'] == "" || $body['tipo'] == "" || $body['nombre'] == ""){
                    $response->getBody()->write('Datos vacios');
                }else {
                    if(strlen($body['clave']) > 3 && ctype_space($body['nombre']) == false) {
                        $existingContent = (string)$response->getBody();
                        $response = $handler->handle($request);
                        $response->getBody()->write($existingContent);
                    }else {
                        $response->getBody()->write('Legajo invalido, debe estar entre 1000 y 2000');
                    }
                    
                }
            } else {
                $response->getBody()->write('Faltan datos');  
            }
            
            return $response;
        }
}


?>