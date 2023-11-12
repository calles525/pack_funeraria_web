<?php

if (!class_exists("cls_db"))
    require_once("cls_db.php");

abstract class cls_tipo_contrato_rcv extends cls_db
{
    protected $id, $nombre, $dano_cosas,
        $dano_personas, $fianza_cuanti, $asistencia_legal,
        $apov, $muerte, $invalidez, $gast_metico, $grua, $estatus;
    public function __construct()
    {
        parent::__construct();
    }

    protected function delete()
    {
        try {
            $sql = $this->db->prepare("UPDATE tipo_contrato_poliza SET estatus_tipo_contrato = ? WHERE id_tipo_contrato = ?");
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

    protected function GetAll()
    {
        $sql = $this->db->prepare("SELECT * FROM tipo_contrato_poliza ORDER BY id_tipo_contrato ASC");
        if ($sql->execute()) $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
        else $resultado = [];
        return $resultado;
    }

    protected function GetOne($id)
    {
        $sql = $this->db->prepare("SELECT * FROM tipo_contrato_poliza WHERE id_tipo_contrato = ?");
        if ($sql->execute([$id])) $resultado = $sql->fetch(PDO::FETCH_ASSOC);
        else $resultado = [];
        return $resultado;
    }

    protected function SearchByNombre()
    {
        $sql = $this->db->prepare("SELECT * FROM tipo_contrato_poliza WHERE des_tipo_contrato = ?");
        if ($sql->execute([$this->nombre])) $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
        else $resultado = [];
        return $resultado;
    }

    protected function GetDuplicados()
    {
        $sql = $this->db->prepare("SELECT * FROM tipo_contrato_poliza WHERE des_tipo_contrato= ? AND id_tipo_contrato = ?");
        if ($sql->execute([$this->nombre, $this->id])) $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
        else $resultado = [];
        return $resultado;
    }

    protected function update()
    {
        try {
            // $res = $this->GetDuplicados();
            // if ($res) {
            //     return [
            //         "data" => [
            //             "res" => "Estas duplicando los datos de otro registro",
            //             "code" => 400
            //         ],
            //         "code" => 400
            //     ];
            // }
            $sql = $this->db->prepare("UPDATE tipo_contrato_poliza SET 
              des_tipo_contrato = ?,
                dano_cosas_tipo_contrato = ?,
                dano_persona_tipo_contrato = ?,
                fianza_cuanti_tipo_contrato = ?,
                asistencia_legal_tipo_contrato = ?,
                apov_tipo_contrato =?,
                muerte_tipo_contrato=?,
                gastos_medicos_tipo_contrato=?,
                invalidez_tipo_contrato=?,
                grua_tipo_contrato=?
                WHERE id_tipo_contrato = ?");
            if ($sql->execute([
                $this->nombre,
                $this->dano_cosas,
                $this->dano_personas,
                $this->fianza_cuanti,
                $this->asistencia_legal,
                $this->apov,
                $this->muerte,
                $this->invalidez,
                $this->gast_metico,
                $this->grua,
                $this->id
            ])) {
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

    protected function save()
    {
        try {
            if (empty($this->nombre)) {
                return [
                    "data" => [
                        "res" => "El nombre del contrato no puede estar vacío",
                        "code" => 400
                    ],
                    "code" => 400
                ];
            }
            $resul = $this->SearchByNombre();
            if ($resul) {
                return [
                    "data" => [
                        "res" => "Este nombre ($this->nombre) ya existe",
                        "code" => 400
                    ],
                    "code" => 400
                ];
            }
            $sql = $this->db->prepare("INSERT INTO tipo_contrato_poliza(
                des_tipo_contrato,
                dano_cosas_tipo_contrato,
                dano_persona_tipo_contrato,
                fianza_cuanti_tipo_contrato,
                asistencia_legal_tipo_contrato,
                apov_tipo_contrato,
                muerte_tipo_contrato,
                gastos_medicos_tipo_contrato,
                invalidez_tipo_contrato,
                grua_tipo_contrato,
                estatus_tipo_contrato)
                VALUES(?,?,?,?,?,?,?,?,?,?,1)");
            $sql->execute([
                $this->nombre,
                $this->dano_cosas,
                $this->dano_personas,
                $this->fianza_cuanti,
                $this->asistencia_legal,
                $this->apov,
                $this->muerte,
                $this->invalidez,
                $this->gast_metico,
                $this->grua
            ]);
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
