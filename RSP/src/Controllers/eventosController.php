<?php
namespace App\Controllers;

use App\Models\Evento;
use App\Models\Inscripto;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Firebase\JWT\JWT;



class EventosController{


    public function addEvento(Request $request, Response $response) {
        
        $evento = new Evento();
        $success = false;
        try {
    
            $body = $request->getParsedBody(); 
    
            $headers = getallheaders();
            $token = $headers['token'] ?? '';
            $key = 'key';

            $decoded = JWT::decode($token, $key, array('HS256'));
            
            $evento->usuario = $decoded->nombre;
            $evento->fecha = date("Y-m-d H:i:s");
            $evento->descripcion = $body['descripcion'];
            
            $msg = json_encode($evento->save());
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

    public function mostrarEvento(Request $request, Response $response) {
        
        $evento = new Evento();
        $headers = getallheaders();
        $token = $headers['token'] ?? '';
        $key = 'key';
        try {
            $decoded = JWT::decode($token, $key, array('HS256'));
        } catch (\Throwable $th) {
            $msg = "Error: " .$th->getMessage();
        }

        if($decoded->tipo == "user") {
            $evento = $evento->select('fecha','descripcion')->where('usuario', $decoded->nombre)->orderBy('fecha', 'desc')->get();
            $success = true;
    
            $rta = array("success" => $success,
                     "evento" => $evento
            );

        } else {
            $evento = $evento->select('usuario','fecha')->orderBy('fecha', 'desc')->get();
            $success = true;
    
            $rta = array("success" => $success,
                     "evento" => $evento,
            );
        }


        $rtaJson = json_encode($rta);
        $response->getBody()->write($rtaJson);
    
        return $response;
    }

    public function modificarEvento(Request $request, Response $response, array $arg) {
        
        $evento = new Evento();
        $headers = getallheaders();
        $id = $arg['id'];
        $token = $headers['token'] ?? '';
        $key = 'key';

        $fecha = date("Y-m-d H:i:s");
        try {
            $decoded = JWT::decode($token, $key, array('HS256'));
        } catch (\Throwable $th) {
            $msg = "Error: " .$th->getMessage();
        }

        if($decoded->tipo == "user") {
            $evento = $evento->where('idE', $id)->update(['fecha' => $fecha]);
            $success = true;
    
            $rta = array("success" => $success,
                     "evento" => $evento
            );

        } else {
            $response->getBody()->write('Tipo de usuario incorrecto');
        }


        $rtaJson = json_encode($rta);
        $response->getBody()->write($rtaJson);
    
        return $response;
    }


}

?>