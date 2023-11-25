<?php
if (!class_exists("cls_db"))
    require("cls_db.php");
abstract class cls_clase_vehiculo extends cls_db
{
    protected $id, $nombre, $estatus;

    public function __construct()
    {
        parent::__construct();
    }

    protected function GetAll()
    {
        $sql = $this->db->prepare("SELECT * FROM clase_vehiculo ORDER BY id_clase_vehi DESC");
        if ($sql->execute()) $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
        else $resultado = [];
        return $resultado;
    }

    protected function save()
    {
        try {
            if (empty($this->nombre)) {
                return [
                    "data" => [
                        "res" => "El nombre de la clase de vehiculo no puede estar vacía",
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
            $sql = $this->db->prepare("INSERT INTO clase_vehiculo(des_clase_vehi,estatus_vehi) VALUES(?,1)");
            $sql->execute([$this->nombre]);
            $this->id = $this->db->lastInsertId();
            if ($sql->rowCount() > 0) {
                return [
                    "data" => [
                        "res" => "Registro exitoso",
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
        } catch (Exception $e) {
            return [
                "data" => [
                    "res" => "Error de consulta: " . $e->getMessage()
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
                        "res" => "Estás duplicando los datos de otro registro",
                        "code" => 400
                    ],
                    "code" => 400
                ];
            }
            $sql = $this->db->prepare("UPDATE clase_vehiculo SET des_clase_vehi=? WHERE id_clase_vehi=?");
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

    protected function delete()
    {
        try {
            $sql = $this->db->prepare("UPDATE clase_vehiculo SET estatus_vehi = ? WHERE id_clase_vehi = ?");
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
    protected function SearchByNombre()
    {
        $sql = $this->db->prepare("SELECT * FROM clase_vehiculo WHERE des_clase_vehi = ?");
        if ($sql->execute([$this->nombre])) $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
        else $resultado = [];
        return $resultado;
    }

    protected function GetDuplicados()
    {
        $sql = $this->db->prepare("SELECT * FROM clase_vehiculo WHERE des_clase_vehi = ? AND id_clase_vehi  = ?");
        if ($sql->execute([$this->nombre, $this->id])) {
            $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $resultado = [];
        }
        return $resultado;
    }

    protected function GetOne($id)
    {
        $sql = $this->db->prepare("SELECT * FROM clase_vehiculo WHERE id_clase_vehi = ?");
        if ($sql->execute([$id])) $resultado = $sql->fetch(PDO::FETCH_ASSOC);
        else $resultado = [];
        return $resultado;
    }
}
