<?php
require_once("./Models/cls_vehiculo.php");

class Con_vehiculo extends cls_vehiculo
{
    public function __construct()
    {
        parent::__construct();
    }

    public function ConsultarTodos()
    {
        $resultado = $this->GetAll();
        Response($resultado, 200);
    }
}
