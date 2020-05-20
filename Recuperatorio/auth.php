<?php
    use \Firebase\JWT\JWT;
    require_once './vendor/autoload.php';
    

    class Auth {

        public static function login($email,$clave,$key){
            $rta = Datos::leerJSON('users.json');        
            $retorno = false;
            if($rta) {
                foreach ($rta as $cliente) {
                    if(strcasecmp($cliente->email, $email) == 0 && strcasecmp($cliente->clave, $clave) == 0){
                        $payload = array (
                            "email" => $cliente->email,
                            "clave" => $cliente->clave,
                        );
                        $retorno = true;
                        break;
                    }
                }
                if($retorno) {
                    $retorno = JWT::encode($payload,$key);
                }
            }
            return $retorno;
        }
        
        public static function decodeToken($header,$key)
        {
            $headers = getallheaders();
            $token = $headers[$header] ?? '';

            try {
                $decoded = JWT::decode($token, $key, array('HS256'));

                return $decoded;

            } catch (\Throwable $th) {
                echo $th->getMessage();
            }

        }

    }
?>