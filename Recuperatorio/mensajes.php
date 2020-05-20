<?php

class Mensajes {
    public $mensaje;
    public $idUsuario;

    public function __construct($idUsuario,$mensaje)
    {
        $this->mensaje = $mensaje ?? null;
        $this->idUsuario =$idUsuario ?? null;
     }

}

?>