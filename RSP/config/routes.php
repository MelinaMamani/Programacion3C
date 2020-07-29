<?php

//aca van las rutas en un group

use Slim\Routing\RouteCollectorProxy;
use App\Controllers\UsuariosController;
use App\Controllers\EventosController;
use App\Middlewares\ValidarDatosMiddleware;
use App\Middlewares\ValidarTipoMiddleware;
use App\Middlewares\VerificarRepetidoMiddleware;
use App\Middlewares\ValidarExistenteMiddleware;
use App\Middlewares\ValidarLoginMiddleware;
use App\Middlewares\ValidarUserTokenMiddleware;
use App\Middlewares\TokenValidoMiddleware;

require '../config/database.php';
require '../src/models/usuario.php';

return function ($app) {
    $app->group('/eventos', function(RouteCollectorProxy $group) {
        $group->post('',EventosController::class . ':addEvento')->add(new ValidarUserTokenMiddleware)->add(new TokenValidoMiddleware);
        $group->get('',EventosController::class . ':mostrarEvento')->add(new TokenValidoMiddleware);
        $group->put('/{id}[/]',EventosController::class . ':modificarEvento')->add(new ValidarUserTokenMiddleware)->add(new TokenValidoMiddleware);
        });

    $app->post('/users[/]',UsuariosController::class . ':postOne')->add(new VerificarRepetidoMiddleware())->add(new ValidarTipoMiddleware())->add(new ValidarDatosMiddleware());

    $app->post('/login[/]',UsuariosController::class . ':login')->add(new ValidarLoginMiddleware())->add(new ValidarExistenteMiddleware());
   
}

?>