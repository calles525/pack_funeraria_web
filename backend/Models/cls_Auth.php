<?php

require "vendor/autoload.php";
require("cls_db.php");


class cls_Auth extends cls_db
{
    protected
        $id,
        $username,
        $nombre,
        $apellido,
        $nacionalidad,
        $cedula,
        $codigoTelefono,
        $telefono,
        $direccion,
        $correo,
        $password,
        $rol,
        $estatus;

    public function __construct()
    {
        parent::__construct();
    }

    protected function sing_in()
    {
        $sql = $this->db->prepare("SELECT * FROM usuarios WHERE username_usuario = ?");
        $PasswordUpdate = false;
        $sql->execute([$this->username]);
        $resultado = $sql->fetch(PDO::FETCH_ASSOC);

        if (isset($resultado["id_usuario"])) {
            if ($resultado["estatus_usuario"] === 0) {
                return [
                    "data" => [
                        "res" => [
                            "text" => "El usuario está desactivado",
                            "code" => 400
                        ]
                    ],
                    "code" => 400
                ];
                return false;
            }
            if ($this->password != $resultado["password_usuario"]) {
                $sql->execute([$resultado["usuario_id"]]);
                return [
                    'data' => [
                        'res' => [
                            'text' => "Su clave es inválida",
                            "code" => 400
                        ]
                    ],
                    'code' => 400
                ];
                return false;
            } else {
                $PasswordUpdate = true;
            }

            $datos = $this->GetOne($resultado["id_usuario"]);
            if (!empty($datos)) {
                return [
                    "data" => [
                        "res" => ["text" => "Login exitoso", "code" => 200],
                        "usuario" => [
                            "username" => $datos["username_usuario"],
                            "user_id" => $datos["id_usuario"],
                            "permisos" => $datos["permisos_usuario"],
                            "rol" => $datos["rol_id_usuario"],
                            "RequireUpdatePass" => $PasswordUpdate
                        ],

                    ],
                    "code" => 200
                ];
            } else {
                return [
                    "data" => [
                        "res" => ["text" => "El usuario no posee permisos para acceder al sistema", "code" => 400],
                    ],
                    "code" => 400
                ];
            }
        }
        return [
            'data' => [
                'res' => ['text' => "El usuario no existe o los datos ingresados son invalidos", 'code' => 400]
            ],
            'code' => 400
        ];
    }

    protected function GetOne($id)
    {
        $sql = $this->db->prepare("SELECT usuarios.*, roles.* FROM usuarios 
        INNER JOIN roles ON roles.id_rol = usuarios.rol_id_usuario WHERE id_usuario = ?");
        $sql->execute([$id]);
        if ($sql->rowCount() > 0) $resultado = $sql->fetch(PDO::FETCH_ASSOC);
        else $resultado = [];
        return $resultado;
    }
}
