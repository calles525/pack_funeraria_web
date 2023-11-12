<?php
date_default_timezone_set("America/Caracas");

// require("./QR/qrlib.php");
require_once("cls_db.php");

abstract class cls_poliza extends cls_db
{
    protected $id, $usuario,
        $nombre, $apellido, $nacionalidad, $fechaNacimiento, $codigoTelefono, $telefono,
        $correo, $direccion,
        //Titular
        $titNombre, $titApellido, $titNacionalidad, $titCedula,
        //Vehiculo
        $placa, $puesto, $ano, $serialMotor, $serialCarroceria, $peso, $capacidad,
        //Vehiculo extra
        $color, $modelo, $marca, $uso, $clase, $tipo,
        //Contrato
        $fechaInicio, $fechaVencimiento, $tipoContrato,
        $estado,
        //Coberturas
        $danoCosas, $danoPersonas, $fianza, $asistencia, $apov, $muerte,
        $invalidez, $medico, $grua,
        //Pago 
        $metodoPago, $referencia, $cantidadDolar, $monto,

        //ID
        $idVehiculo, $idCliente, $idPrecioDolar, $idDebitoCredito, $idCobertura,
        $idColor, $idModelo, $idMarca;


    protected function vencer()
    {
        date_default_timezone_set("America/Caracas");
        $diaActual = date("Y-m-d");
        $diaCinco = date("Y-m-d", strtotime("+30 day"));
        $sql = $this->db->prepare("SELECT contrato_poliza.*, cliente.*, vehiculo.* FROM contrato_poliza
        INNER JOIN cliente ON cliente.id_cliente = contrato_poliza.cliente_id_contrato_pol
        INNER JOIN vehiculo ON vehiculo.id_vehiculo = contrato_poliza.vehiculo_id_contrato_pol
        where contrato_poliza.fecha_vencimiento_contrato_pol > :diaActual AND 
        contrato_poliza.fecha_vencimiento_contrato_pol <= :diaCinco
        ORDER BY contrato_poliza.id_contrato_pol ASC");
        $sql->bindParam(':diaActual', $diaActual, PDO::PARAM_STR);
        $sql->bindParam(':diaCinco', $diaCinco, PDO::PARAM_STR);
        if ($sql->execute()) {
            $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $resultado = [];
        }
        return $resultado;
    }
}
