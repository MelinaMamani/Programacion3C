<?php

require_once __DIR__ . '/../vendor/autoload.php';
use \Slim\Factory\AppFactory;
//use \Config\Database;
//new Database();
require __DIR__ .'/database.php';
use Psr\Http\Message\ServerRequestInterface;


$app = AppFactory::create();
$app->setBasePath('/rsp/public');
$app->addRoutingMiddleware();


$customErrorHandler = function (
    ServerRequestInterface $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($app) {


    $payload = ['error' => $exception->getMessage()];

    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write(
        json_encode($payload, JSON_UNESCAPED_UNICODE)
    );

    return $response;
};

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);


//Registrar rutas

(require_once __DIR__ . '/routes.php')($app);

//Registrar middlewares

(require_once __DIR__ . '/middleware.php')($app);


return $app;

?>