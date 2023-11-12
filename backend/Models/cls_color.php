<?php
if (!class_exists("cls_db"))
    require("cls_db.php");

abstract class cls_color extends cls_db
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function GetAll()
    {
        $sql = $this->db->prepare("SELECT * FROM color_vehiculo");
        if ($sql->execute()) $resultado = $sql->fetch(PDO::FETCH_ASSOC);
        else $resultado = [];
        return $resultado;
    }
}
