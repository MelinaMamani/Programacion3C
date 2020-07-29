<?php

namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use \Firebase\JWT\JWT;

class TokenValidoMiddleware {
    public function __invoke(Request $request, RequestHandler $handler): Response
        {
            
            $response = new Response();
            
            $headers = getallheaders();
            $token = $headers['token'] ?? '';
            $key = 'key';
            try {
                $decoded = JWT::decode($token, $key, array('HS256'));
            } catch (\Throwable $th) {
                $decoded= null;
              }
            
            
        
            if ($decoded == null) {
                $response->getBody()->write('Token invalido');
            } else {
                $existingContent = (string) $response->getBody();
                $response = $handler->handle($request);
                $response->getBody()->write($existingContent);
            }
            
            return $response;
        }
}
?>