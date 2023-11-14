<?php
if (!class_exists("cls_db"))
    require_once("cls_db.php");
abstract class cls_uso_vehiculo extends cls_db
{
    protected $id, $nombre, $estatus;
    public function __construct()
    {
        parent::__construct();
    }

    protected function delete()
    {
        try {
            $sql = $this->db->prepare("UPDATE uso_vehiculo SET estatus_uso_vehi = ? WHERE id_uso_vehi =?");
            if ($sql->execute([$this->estatus,$this->id])) {
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

    protected function GetOne($id)
    {
        $sql = $this->db->prepare("SELECT * FROM uso_vehiculo WHERE id_uso_vehi = ?");
        if ($sql->execute([$id])) $resultado = $sql->fetch(PDO::FETCH_ASSOC);
        else $resultado = [];
        return $resultado;
    }

    protected function GetAll()
    {
        $sql = $this->db->prepare("SELECT * FROM uso_vehiculo ORDER BY 	id_uso_vehi DESC");
        if ($sql->execute()) $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
        else $resultado = [];
        return $resultado;
    }

    protected function SearchByNombre()
    {
        $sql = $this->db->prepare("SELECT * FROM uso_vehiculo WHERE des_uso_vehi = ?");
        if ($sql->execute([$this->nombre])) $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
        else $resultado = [];
        return $resultado;
    }
    protected function GetDuplicados()
    {
        $sql = $this->db->prepare("SELECT * FROM uso_vehiculo WHERE des_uso_vehi = ? AND id_uso_vehi =?");
        if ($sql->execute([$this->nombre, $this->id])) $resultado = $sql->fetch(PDO::FETCH_ASSOC);
        else $resultado = [];
        return $resultado;
    }
    protected function Save()
    {
        try {
            if (empty($this->nombre)) {
                return [
                    "data" => [
                        "res" => "El nombre del uso del vehiculo no puede estar vacío",
                        "code" => 400
                    ],
                    "code" => 400
                ];
            }
            $result = $this->SearchByNombre();
            if ($result) {
                return [
                    "data" => [
                        "res" => "Este nombre ($this->nombre) ya existe",
                        "code" => 400
                    ],
                    "code" => 400
                ];
            }
            $sql = $this->db->prepare("INSERT INTO uso_vehiculo(des_uso_vehi,estatus_uso_vehi) VALUES (?,1)");
            $sql->execute([$this->nombre]);
            $this->id = $this->db->lastInsertId();
            if ($sql->rowCount() > 0) {
                return [
                    "data" => [
                        "res" => "Resgistro exitoso",
                        "code" => 200
                    ],
                    "code" => 200
                ];
            }
            return [
                "data" => [
                    "res" => "El registro ha fallado",
                    "code" => 400
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

    protected function update()
    {
        try {
            $res = $this->GetDuplicados();
            if ($res) {
                return [
                    "data" => [
                        "res" => "Estas duplicando los datos de otro registro",
                        "code" => 400
                    ],
                    "code" => 400
                ];
            }
            $sql = $this->db->prepare("UPDATE uso_vehiculo SET des_uso_vehi = ? WHERE id_uso_vehi = ?");
            if ($sql->execute([$this->nombre, $this->id])) {
                return [
                    "data" => [
                        "res" => "Actualización de datos exitosa",
                        "code" => 200
                    ],
                    "code" => 200
                ];
            }
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
