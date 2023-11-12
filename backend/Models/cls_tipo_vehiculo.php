<?php
if (!class_exists("cls_db"))
    require_once("cls_db.php");

abstract class cls_tipo_vehiculo extends cls_db
{
    protected $id, $nombre, $precio, $idContrato, $estatus;

    public function __construct()
    {
        parent::__construct();
    }

    protected function SearchByNombre()
    {
        $sql = $this->db->prepare("SELECT * FROM tipo_vehiculo WHERE des_tipo_vehi = ?");
        if ($sql->execute([$this->nombre])) $resultado = $sql->fetch(PDO::FETCH_ASSOC);
        else $resultado = [];
        return $resultado;
    }

    protected function GetAll()
    {
        $sql = $this->db->prepare("SELECT * FROM  tipo_vehiculo");
        if ($sql->execute())
            $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
        else
            $resultado = [];
        return $resultado;
    }

    protected function save()
    {
        try {
            if (empty($this->nombre)) {
                return [
                    "data" => [
                        "res" => "El nombre del tipo de vehiculo no puede estar vacío",
                        "code" => 400
                    ],
                    "code" => 400
                ];
            }
            $result = $this->SearchByNombre();
            if (isset($result[0])) {
                return [
                    "data" => [
                        "res" => "Este vehiculo ($this->nombre) ya existe",
                        "code" => 400
                    ],
                    "code" => 400
                ];
            }

            $sql = $this->db->prepare("INSERT INTO tipo_vehiculo(des_tipo_vehi,	estatus_tipo_vehi) VALUES(?,1)");
            $sql->execute([$this->nombre]);
            $this->id = $this->db->lastInsertId();
            if ($sql->rowCount() > 0) {
                return [
                    "data" => [
                        "res" => "Registro exitoso",
                        "id" => $this->id
                    ],
                    "code" => 200
                ];
            }
            return [
                "data" => [
                    "res" => "El registro ha fallado"
                ],
                "code" => 400
            ];
        } catch (PDOException $e) {
            return [
                "data" => [
                    'res' => "Error de consulta: " . $e->getMessage()
                ],
                "code" => 400
            ];
        }
    }
}
