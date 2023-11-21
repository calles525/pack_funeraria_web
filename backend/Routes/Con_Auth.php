<?php
require_once("./Models/cls_Auth.php");

class Con_Auth extends cls_Auth
{
    public function __construct()
    {
        parent::__construct();
        $this->id = isset($_POST["ID"]) ? $_POST["ID"] : null;
        $this->username = isset($_POST["Username"]) ? $_POST["Username"] : null;
        $this->nombre = isset($_POST["Nombre"]) ? $_POST["Nombre"] : null;
        $this->apellido = isset($_POST["Apellido"]) ? $_POST["Apellido"] : null;
        $this->nacionalidad = isset($_POST["Nacionalidad"]) ? $_POST["Nacionalidad"] : null;
        $this->cedula = isset($_POST["Cedula"]) ? $_POST["Cedula"] : null;
        $this->codigoTelefono = isset($_POST["Codigo"]) ? $_POST["Codigo"] : null;
        $this->telefono = isset($_POST["Telefono"]) ? $_POST["Telefono"] : null;
        $this->direccion = isset($_POST["Direccion"]) ? $_POST["Direccion"] : null;
        $this->correo = isset($_POST["Correo"]) ? $_POST["Correo"] : null;
        $this->password = isset($_POST["Password"]) ? $_POST["Password"] : null;
        $this->rol = isset($_POST["Rol"]) ? $_POST["Rol"] : null;
        $this->estatus = isset($_POST["Estatus"]) ? $_POST["Estatus"] : null;
    }

    public function login()
    {
        if (!isset($this->username) || !isset($this->password)) {
            Response([
                "res" => "El nombre de usuario y la clave son obligatorios"
            ], 400);
            return false;
        }

        $resultado = $this->sing_in();
       
        if ($resultado["code"] == 200) {
            $token = createToken(
                $resultado["data"]["usuario"]["user_id"], 
                $resultado["data"]["usuario"]["permisos"]);
            Response([
                "data" => [
                    "usuario" => [
                        $resultado["data"]["usuario"]
                    ],
                    "token" => $token,
                    "res" => $resultado["data"]["res"],
                    "code" => $resultado["code"],
                ],
            ], 200);
            return false;
        } else {
            Response([
                "data" => [
                    "res" => $resultado["data"]["res"]
                ],
            ], 400);
        }
    }
}
