<?php

class Usuario {
    public $email;
    public $clave;
    public $tipo;
    public $imagen1;
    public $imagen2;
    public $id;

    public function __construct($id,$email,$clave,$tipo,$imagen1,$imagen2)
    {
        $this->id = $id ?? null;
        $this->email = $email ?? null;
        $this->clave =$clave ?? null;
        $this->tipo = $tipo ?? null;
        $this->imagen1 = $imagen1 ?? null;
        $this->imagen2 = $imagen2 ?? null;
    }

    
}

?>