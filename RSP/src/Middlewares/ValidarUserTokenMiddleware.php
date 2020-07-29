<?php

namespace App\Middlewares;

use App\Models\Usuario;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use \Firebase\JWT\JWT;

class ValidarUserTokenMiddleware {
    public function __invoke(Request $request, RequestHandler $handler): Response
        {
            
            $response = new Response();

            $headers = getallheaders();
            $token = $headers['token'] ?? '';
            $key = 'key';

            $decoded = JWT::decode($token, $key, array('HS256'));
            
        
            if ($decoded->tipo == "user") {
                
                $existingContent = (string) $response->getBody();
                $response = $handler->handle($request);
                $response->getBody()->write($existingContent);
                
            } else {
                $response->getBody()->write('Usuario no es tipo user');  
            }
            
            return $response;
        }
}
?>