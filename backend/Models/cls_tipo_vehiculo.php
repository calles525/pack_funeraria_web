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
        $sql = $this->db->prepare("SELECT tipo_precio_poliza.*, tipo_vehiculo.*,tipo_contrato_poliza.* 
        FROM  tipo_precio_poliza
        INNER JOIN tipo_vehiculo ON tipo_vehiculo.id_tipo_vehi = tipo_precio_poliza.tipo_vehi_id_tipo_precio_poliza 
        INNER JOIN tipo_contrato_poliza ON tipo_contrato_poliza.id_tipo_contrato =  tipo_precio_poliza.tipo_contrato_id_tipo_precio_poliza
        WHERE estatus_tipo_vehi = 1");
        if ($sql->execute())
            $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
        else
            $resultado = [];
        return $resultado;
    }

    protected function delete()
    {
        try {
            $sql = $this->db->prepare("UPDATE tipo_vehiculo SET estatus_tipo_vehi = ? WHERE id_tipo_vehi = ?");
            if ($sql->execute([$this->estatus, $this->id])) {
                return [
                    "data" => [
                        "res" => "Estatus modificado",
                        "code" => 200,
                    ],
                    "code" => 200
                ];
            }
        } catch (PDOException $e) {
            return [
                "data" => [
                    "res" => "Error de consulta: " . $e->getMessage()
                ],
                "code" => 400
            ];
        }
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
