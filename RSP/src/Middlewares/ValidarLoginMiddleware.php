<?php

namespace App\Middlewares;

use App\Models\Usuario;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ValidarLoginMiddleware {
    public function __invoke(Request $request, RequestHandler $handler): Response
        {
            
            $response = new Response();
            $usuario = new Usuario;

            $body =$request->getParsedBody();

            $nombre = $usuario->where('nombre', $body['usuario'])->value('nombre');
            $mail = $usuario->where('email', $body['usuario'])->value('email');
            $claveEmail = $usuario->where('email', $body['usuario'])->value('clave');
            $claveNombre = $usuario->where('nombre', $body['usuario'])->value('clave');
            
            
            if (($nombre == $body['usuario'] || $mail == $body['usuario']) && 
            ($claveEmail == $body['clave'] || $claveNombre == $body['clave'])) {
                
                $existingContent = (string) $response->getBody();
                $response = $handler->handle($request);
                $response->getBody()->write($existingContent);
                
            } else {
                $response->getBody()->write('Contraseña incorrecta');  
            }
            
            return $response;
        }
}
?>