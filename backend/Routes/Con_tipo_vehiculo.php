<?php
require_once("./Models/cls_tipo_vehiculo.php");

class Con_tipo_vehiculo extends cls_tipo_vehiculo
{
    public function __construct()
    {
        parent::__construct();
        $this->id = isset($_POST["ID"]) ? $_POST["ID"] : null;
        $this->nombre = isset($_POST["Nombre"]) ? $_POST["Nombre"] : null;
        $this->precio = isset($_POST["Precio"]) ? $_POST["Precio"] : null;
        $this->estatus = isset($_POST["Estatus"]) ? $_POST["Estatus"] : null;
    }

    public function ConsultarTodos()
    {
        $resultado = $this->GetAll();
        Response($resultado, 200);
    }

    public function registrar()
    {
        $resultado = $this->save();
        Response($resultado, 200);
    }

    public function eliminar()
    {
        $resultado = $this->delete();
        Response($resultado, 200);
    }
}
