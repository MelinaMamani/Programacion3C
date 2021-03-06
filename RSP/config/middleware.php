<?php
    use Slim\App;
    use App\Middlewares\AfterMiddleware;
    

    return function(App $app) {
        $app->addBodyParsingMiddleware();
        
        $app->add(new AfterMiddleware());
        

    }
?>