<?php
if (!class_exists("cls_db"))
    require("cls_db.php");

abstract class cls_vehiculo extends cls_db
{
    protected $placa;

    public function __construct()
    {
        parent::__construct();
    }

    protected function GetAll()
    {
        $sql = $this->db->prepare("SELECT 
        vehiculo.*,
        uso_vehiculo.*,
        tipo_vehiculo.*,
        modelo_vehiculo.*,
        marca_vehiculo.*,
        clase_vehiculo.*, 
        color_vehiculo.*
        FROM vehiculo
        INNER JOIN clase_vehiculo ON clase_vehiculo.id_clase_vehi = vehiculo.clase_id_vehiculo
        INNER JOIN tipo_vehiculo ON tipo_vehiculo.id_tipo_vehi  = vehiculo.tipo_id_vehiculo
        INNER JOIN modelo_vehiculo ON modelo_vehiculo.id_modelo_vehi = vehiculo.modelo_id_vehiculo
        INNER JOIN marca_vehiculo ON marca_vehiculo.id_marca_vehi = modelo_vehiculo.marca_id_modelo_vehi
        INNER JOIN uso_vehiculo ON uso_vehiculo.id_uso_vehi = vehiculo.uso_id_vehiculo
        INNER JOIN color_vehiculo ON color_vehiculo.id_color  = vehiculo.color_id_vehiculo
        ");
        if ($sql->execute()) $resultado = $sql->fetch(PDO::FETCH_ASSOC);
        else $resultado = [];
        return $resultado;
    }
}
