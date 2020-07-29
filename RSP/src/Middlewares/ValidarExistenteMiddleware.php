<?php

namespace App\Middlewares;

use App\Models\Usuario;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ValidarExistenteMiddleware {
    public function __invoke(Request $request, RequestHandler $handler): Response
        {
            
            $response = new Response();
            $usuario = new Usuario;

            $body =$request->getParsedBody();

            $query = $usuario->where('email', $body['usuario'])->exists(); 
            $query2 = $usuario->where('nombre', $body['usuario'])->exists();
            
            if ($query == true || $query2 == true) {
                
                $existingContent = (string) $response->getBody();
                $response = $handler->handle($request);
                $response->getBody()->write($existingContent);
                
            } else {
                $response->getBody()->write('Usuario inexistente');  
            }
            
            return $response;
        }
}
?>