<?php 
require_once("./Models/cls_poliza.php");

class Con_poliza extends cls_poliza{
    public function ConsultarVencer()
    {
        $resultado = $this->Vencer();
        Response($resultado, 200);
    }
}
