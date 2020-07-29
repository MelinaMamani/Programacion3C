<?php

namespace App\Middlewares;

use App\Models\Usuario;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class VerificarRepetidoMiddleware {
    public function __invoke(Request $request, RequestHandler $handler): Response
        {
            
            $response = new Response();
            $usuario = new Usuario;

            $body =$request->getParsedBody();

            $mail = $usuario->where('email', $body['email'])->exists();
            $nombre = $usuario->where('nombre', $body['nombre'])->exists();
            
            if ($mail == false && $nombre == false) {
                
                $existingContent = (string) $response->getBody();
                $response = $handler->handle($request);
                $response->getBody()->write($existingContent);
                
            } else {
                $response->getBody()->write('Usuario repetido');  
            }
            
            return $response;
        }
}
?>