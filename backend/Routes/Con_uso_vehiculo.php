<?php
require_once("./Models/cls_uso_vehiculo.php");

class Con_uso_vehiculo extends cls_uso_vehiculo
{
    public function __construct()
    {
        parent::__construct();
        $this->id = isset($_POST["ID"]) ? $_POST["ID"] : null;
        $this->nombre = isset($_POST["Nombre"]) ? $_POST["Nombre"] : null;
        $this->estatus = isset($_POST["Estatus"]) ? $_POST["Estatus"] : null;
    }

    public function ConsultarUno()
    {
        $resultado = $this->GetOne($_GET["ID"]);
        Response($resultado, 200);
    }
    public function eliminar()
    {
        $resultado = $this->delete();
        Response($resultado, 200);
    }
    public function ConsultarTodos()
    {
        $resultado = $this->GetAll();
        Response($resultado, 200);
    }

    public function registrar()
    {
        $resultado = $this->Save();
        Response($resultado["data"], $resultado["code"]);
    }

    public function actualizar()
    {
        $resultado = $this->update();
        Response($resultado["data"], $resultado["code"]);
    }
}
