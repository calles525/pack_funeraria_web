<?php
require_once("./Models/cls_cliente.php");

class Con_cliente extends cls_cliente
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
