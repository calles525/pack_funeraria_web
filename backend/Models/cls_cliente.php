<?php
if (!class_exists("cls_db"))
    require("cls_db.php");

abstract class cls_cliente extends cls_db
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function GetAll()
    {
        $sql = $this->db->prepare("SELECT * FROM cliente");
        if ($sql->execute()) $resultado = $sql->fetch(PDO::FETCH_ASSOC);
        else $resultado = [];
        return $resultado;
    }
}
