<?php
//aca van todas las consultas de queries
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\Usuario;
use \Firebase\JWT\JWT;




class UsuariosController{


    public function postOne(Request $request, Response $response) {
        
        $usuario = new Usuario;
        $success = false;
        try {
    
            $body = $request->getParsedBody(); 
    
            $usuario->email = $body['email'];
            $usuario->clave =$body['clave'];
            $usuario->tipo = $body['tipo'];
            $usuario->nombre = $body['nombre'];
            
            $msg = json_encode($usuario->save());
            $success = true;
          

        } catch (\Throwable $th) {
            $msg = "Error: " .$th->getMessage();
        }
    
        $rta = array("success" => $success,
                     "mensaje" => $msg
        );
    
        $rtaJson = json_encode($rta);
        $response->getBody()->write($rtaJson);
    
        return $response;
    }

    public function login(Request $request, Response $response) {
        
        $usuario = new Usuario;
        $key = 'key';
        $success = false;
        try {
    
            $body = $request->getParsedBody();

            $payload = array (
                "id"    => $usuario->where('email', $body['usuario'])->value('id'),
                "email" => $usuario->where('email', $body['usuario'])->value('email'),
                "clave" => $usuario->where('email', $body['usuario'])->value('clave'),
                "tipo"  => $usuario->where('email', $body['usuario'])->value('tipo'),
                "nombre"=> $usuario->where('email', $body['usuario'])->value('nombre'),
            );
            
            $payload = array (
                "id"    => $usuario->where('nombre', $body['usuario'])->value('id'),
                "email" => $usuario->where('nombre', $body['usuario'])->value('email'),
                "clave" => $usuario->where('nombre', $body['usuario'])->value('clave'),
                "tipo"  => $usuario->where('nombre', $body['usuario'])->value('tipo'),
                "nombre"=> $usuario->where('nombre', $body['usuario'])->value('nombre'),
            );

            $msg = JWT::encode($payload,$key);
            $success = true;
          

        } catch (\Throwable $th) {
            $msg = "Error: " .$th->getMessage();
        }
    
        $rta = array("success" => $success,
                     "mensaje" => $msg
        );
    
        $rtaJson = json_encode($rta);
        $response->getBody()->write($rtaJson);
    
        return $response;
    }


}

?>