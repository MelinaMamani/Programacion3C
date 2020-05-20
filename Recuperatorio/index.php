<?php

require_once './usuario.php';
require_once './datos.php';
require_once './auth.php';
require_once './mensajes.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . './vendor/autoload.php';

$app = AppFactory::create();
$app->setBasePath('/Recuperatorio');

$usuarios = array();
$mensajes = array();

$app->post('/users', function (Request $request, Response $response) {    
    $campos = $request->getParsedBody();
    $success = "Registrado";
    $email = $campos['email'];
    $clave = $campos['clave'];
    $tipo = $campos['tipo'];
    $id = strval(rand(1,100));

    $files = $request->getUploadedFiles();
    $file1 = $files['foto1'];
    $imagen1 = './images/users/'."1"."-". $file1->getClientFilename();

    $file2 = $files['foto2'];
    $imagen2 = './images/users/'."2"."-". $file2->getClientFilename();

    if(isset($id) && isset($email) && isset($clave) && isset($tipo)) {
            
        if(!empty($id) && !empty($email) && !empty($clave) && !empty($tipo))
        {
            $usuario = new Usuario($id,$email,$clave,$tipo,$imagen1,$imagen2);
            $usuarios = Datos::leerJson('users.json'); 

            if($usuarios == false)
            {
                $usuarios = array();
                array_push($usuarios,$usuario);
                $usuarios = Datos::guardarUno('users.json',$usuarios);
                $file1->moveTo($imagen1);
                $file2->moveTo($imagen2);
                } else {
                    $usuarios = Datos::guardarJson('users.json',$usuario); 
                    $file1->moveTo($imagen1);
                    $file2->moveTo($imagen2);
                }
            }
            else 
            {
                $success = 'Tipo de cliente no valido';
            }
    } else {
        $success = "Faltan datos";
    }    

    $rta = array("success" => $success,
    "mensaje" => "usuario nuevo",
    "campos" => $campos,
    "imagenes" => $files);

    $rtaJson = json_encode($rta);
    $response->getBody()->write($rtaJson);

    return $response
    ->withHeader('Content-Type', 'application/json')
    ->withStatus(200);
});

$app->post('/login', function (Request $request, Response $response) {    
    $campos = $request->getParsedBody();
    $success = "Logueado";
    $email = $campos['email'];
    $clave = $campos['clave'];
    $jwt = "no existe";

    if(isset($email) && isset($clave)) {
        $_SESSION['Usuario'] = Auth::login($email,$clave,'pro3-parcial');
        $jwt = $_SESSION['Usuario'];
        if(!$_SESSION['Usuario'])
        {
            $success = "email o clave incorrectos";
        }
    } else
    {
        $success = "cargar email y clave nuevamente";
    }

    $rta = array("success" => $success,
    "JWT" => $jwt);

    $rtaJson = json_encode($rta);
    $response->getBody()->write($rtaJson);

    return $response
    ->withHeader('Content-Type', 'application/json')
    ->withStatus(200);
});

$app->group('/mensajes', function ($group){
    $group->get('/', function ($request, $response) {    
        $mensajes = "Error al mostrar"; 
        $usuario = "Error al mostrar usuario";
        $usuarios = Datos::leerJson('users.json');
        $tipo = "-";
        $fecha = "-";
        
        $decoded = Auth::decodeToken('token','pro3-parcial');
                if($decoded)
                {
                    $usuario = $decoded;

                    foreach ($usuarios as $user) {
                        if ($user->email == $usuario->email) {
                            $tipo = $user->tipo;
                        }
                    }

                    if ($tipo == "user") {
                        $lista = Datos::leerJSON('mensajes.json');
                        $mensajes = $lista->mensaje;
                        $fecha = date("F d Y", filectime('mensajes.json'));
                    }
                    else {
                        $success = "es admin";
                    }
                    
                }
        $rta = array("Mensajes" => $mensajes,
        "Fecha" => $fecha,
        "tipo-usuario" => $tipo);

        $rtaJson = json_encode($rta);
        $response->getBody()->write($rtaJson);

        return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
    });
    
    $group->post('/', function ($request, $response) {    
        $decoded = Auth::decodeToken('token','pro3-parcial');
        
        if($decoded)
        {
            $campos = $request->getParsedBody();
            $success = "mensaje mandado";
            $idUsuario = $campos['idUsuario'];
            $mensaje = $campos['mensaje'];
            
            if(isset($idUsuario) && isset($mensaje)) {
                    
                if(!empty($idUsuario) && !empty($mensaje))
                {
                    $mensajes = Datos::leerJson('mensajes.json');

                    $mensaje = new Mensajes($idUsuario,$mensaje);

                    if($mensajes == false)
                    {
                        $mensajes = array();
                        array_push($mensajes,$mensaje);
                        $mensajes = Datos::guardarUno('mensajes.json',$mensajes);
                    }
                }
                else 
                {
                    $success = 'mensaje no valido';
                }
            } else {
                $success = "faltan datos";
            }
        } else {
            $success = "token incorrecto";
        }

        $rta = array("success" => $success,
        "campos" => $campos);

        $rtaJson = json_encode($rta);
        $response->getBody()->write($rtaJson);

        return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
    });
});

$app->run();


?>