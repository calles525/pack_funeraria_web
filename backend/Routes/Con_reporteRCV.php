<?php
include("./FPDF/fpdf.php");
include("./Models/cls_poliza.php");
class MiCliente extends cls_poliza
{

}
$a = new MiCliente();
$datos = $a->GetOne($_GET["ID"]);
$renovacion = "";
$cap = "";
$capTotal = "";

if ($datos && count($datos) > 0) {
    if ($datos[0]["poliza_renovacion"] < 10) {
        $renovacion = "0" . $datos[0]["poliza_renovacion"];
    } else {
        $renovacion = $datos[0]["poliza_renovacion"];
    }

    if ($datos[0]["vehiculo_peso"] > 0) {
        $cap = $datos[0]["vehiculo_peso"];
    }

    if ($datos[0]["vehiculo_capTon"] > 0) {
        $capTotal = $datos[0]["vehiculo_capTon"];
    }
}

$Pdf = new FPDF("L", "mm", "legal");
$Pdf->AddPage("P");
$Pdf->Image("./Img/rubro2.jpg", 49, 224, 36, 32);
// $Pdf->Image($datos[0]["poliza_qr"], 100, 232, 23, 23);
$Pdf->SetFont("Arial", "", 12);
$Pdf->SetTextColor(000);
$Pdf->SetFillColor(255, 255, 255);
$Pdf->SetFont("Arial", "B", "10");
$Pdf->Cell(100, 50, "");
$Pdf->Cell(100, 50, "Contrato NRO: 00000" . $datos[0]["poliza_id"] . " - " . $renovacion);
$Pdf->Ln(1);
$Pdf->Cell(85, 10, "");
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(200, 10, "", 0, 1, "C");
$Pdf->Cell(28, 80, "Vigencia desde: ");
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(88, 80, "" . date("d/m/Y", strtotime($datos[0]["poliza_fechaInicio"])));
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(28, 80, "Vigencia Hasta: ");
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(35, 80, "" . date("d/m/Y", strtotime($datos[0]["poliza_fechaVencimiento"])));
$Pdf->Ln(5);
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(14, 80, "Titular: ");
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(102, 80, "" . utf8_decode($datos[0]["titular_nombre"] . " " . $datos[0]["titular_apellido"]));
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(13, 80, "CI/RIF: ");
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(4, 80, "" . $datos[0]["titular_cedula"]);
$Pdf->Cell(170, 80, "");
$Pdf->Ln(5);
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(22, 80, "Contratante: " . utf8_decode($datos[0]["cliente_nombre"]) . "  " . utf8_decode($datos[0]["cliente_apellido"]));
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(94, 80, "");
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(13, 80, "CI/RIF: ");
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(4, 80, "" . $datos[0]["cliente_cedula"]);
$Pdf->Cell(160, 80, "");
$Pdf->Ln(5);
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(17, 80, "Telefono: ");
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(8, 80, "" . $datos[0]["cliente_telefono"]);
$Pdf->Cell(91, 80, "-");
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(27, 80, "Tel. Habitacion: ");
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(8, 80, "");
$Pdf->Cell(160, 80, "-");
$Pdf->Ln(5);
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(13, 80, "E-Mail: ");
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(103, 80, "" . $datos[0]["cliente_correo"]);
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(14, 80, "Asesor: ");
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(102, 80, "" . $datos[0]["usuario_nombre"]);
$Pdf->Ln(5);
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(27, 80, "Dir. Habitacion: ");
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(100, 80, strtoupper($datos[0]["cliente_direccion"]));
$Pdf->Ln(9);
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(100, 80, "Tipo Contrato: " . $datos[0]["contrato_nombre"]);
$Pdf->Ln(9);
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(60, 80, "CARACTERISTICA DEL VEHICULO");
$Pdf->Ln(6);
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(12, 80, "Marca: ");
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(88, 80, "" . $datos[0]["marca_nombre"]);
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(16, 80, "Modelo: ");
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(60, 80, "" . $datos[0]["modelo_nombre"]);
$Pdf->Ln(5);
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(12, 80, "Clase: ");
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(88, 80, strtoupper($datos[0]["clase_nombre"]));
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(10, 80, "Uso: ");
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(60, 80, "" . $datos[0]["usoVehiculo_nombre"]);
$Pdf->Ln(5);
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(12, 80, "Color: ");
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(88, 80, "" . $datos[0]["color_nombre"]);
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(10, 80, utf8_decode("Año"));
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(60, 80, "" . $datos[0]["vehiculo_año"]);
$Pdf->Ln(5);
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(18, 80, "Ser. Carr.: " . $datos[0]["vehiculo_serialCarroceria"]);
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(82, 80, "");
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(21, 80, "Ser. Motor.: " . $datos[0]["vehiculo_serialMotor"]);
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(60, 80, "");
$Pdf->Ln(5);
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(12, 80, "Placa: ");
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(88, 80, strtoupper("" . $datos[0]["vehiculo_placa"]));
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(10, 80, "Tipo: ");
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(60, 80, strtoupper($datos[0]["tipoVehiculo_nombre"]));
$Pdf->Ln(5);
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(16, 80, "Puestos: ");
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(84, 80, "" . $datos[0]["vehiculo_puesto"]);
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(12, 80, "Peso: ");
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(26, 80, "" . $cap);
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(18, 80, "Cap. Ton: ");
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(60, 80, "" . $capTotal);
$Pdf->Ln(12);
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(90, 75, "GARANTIAS RESPONSABILIDAD CIVIL");
$Pdf->Cell(60, 75, "MONTO GARANTIAS");
$Pdf->Cell(60, 75, "PAGOS Bs.");
$Pdf->Ln(10);
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(90, 70, utf8_decode("POR DAÑOS A COSAS: "));
$Pdf->Cell(60, 70, "" . $datos[0]["dañoCosas"]);
$Pdf->Cell(60, 70, "" . $datos[0]["cobertura_danoCosas"]);
$Pdf->Ln(6);
$Pdf->Cell(90, 70, utf8_decode("POR DAÑOS A PERSONAS: "));
$Pdf->Cell(60, 70, "" . $datos[0]["dañoPersonas"]);
$Pdf->Cell(60, 70, "" . $datos[0]["cobertura_danoPersonas"]);
$Pdf->Ln(6);
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(90, 70, "TOTAL RESPONSABILIDAD CIVIL:");
$Pdf->Cell(60, 70, "" . $datos[0]["dañoCosas"] + $datos[0]["dañoPersonas"] . ".00");
$Pdf->Cell(60, 70, "" . $datos[0]["cobertura_danoCosas"] + $datos[0]["cobertura_danoPersonas"] . ".00");
$Pdf->Ln(8);
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(90, 70, "FINANZA FACULTATIVA:");
$Pdf->Cell(60, 70, "" . $datos[0]["fianzaCuanti"]);
$Pdf->Cell(60, 70, "" . $datos[0]["cobertura_fianzaCuanti"]);
$Pdf->Ln(6);
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(90, 70, "ASISTENCIA LEGAL:");
$Pdf->Cell(60, 70, "" . $datos[0]["asistenciaLegal"]);
$Pdf->Cell(60, 70, "" . $datos[0]["cobertura_asistenciaLegal"]);
$Pdf->Ln(6);
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(90, 70, "TOTAL ASISTENCIA LEGAL:");
$Pdf->Cell(60, 70, "" . $datos[0]["fianzaCuanti"] + $datos[0]["asistenciaLegal"] . ".00");
$Pdf->Cell(60, 70, "" . $datos[0]["cobertura_fianzaCuanti"] + $datos[0]["cobertura_asistenciaLegal"] . ".00");
$Pdf->Ln(6);
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(90, 70, "A.P.O.V. (accidentes por ocupante de vehiculos.):");
$Pdf->Cell(60, 70, "" . $datos[0]["apov"]);
$Pdf->Cell(60, 70, "" . $datos[0]["cobertura_apov"]);
$Pdf->Ln(6);
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(90, 70, "MUERTE:");
$Pdf->Cell(60, 70, "" . $datos[0]["muerte"]);
$Pdf->Cell(60, 70, "" . $datos[0]["cobertura_muerte"]);
$Pdf->Ln(6);
$Pdf->Cell(90, 70, "INVALIDEZ:");
$Pdf->Cell(60, 70, "" . $datos[0]["invalidez"]);
$Pdf->Cell(60, 70, "" . $datos[0]["cobertura_invalidez"]);
$Pdf->Ln(6);
$Pdf->Cell(90, 70, "GASTOS MEDICOS:");
$Pdf->Cell(60, 70, "" . $datos[0]["gastosMedicos"]);
$Pdf->Cell(60, 70, "" . $datos[0]["cobertura_gastosMedicos"]);
$Pdf->Ln(6);
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(90, 70, "TOTAL ACCID. OCUPANTES:");
$Pdf->Cell(60, 70, "" . $datos[0]["muerte"] + $datos[0]["invalidez"] + $datos[0]["gastosMedicos"] . ".00");
$Pdf->Cell(60, 70, "" . $datos[0]["cobertura_muerte"] + $datos[0]["cobertura_invalidez"] + $datos[0]["cobertura_gastosMedicos"] . ".00");
$Pdf->Ln(6);
$Pdf->SetFont('Arial', '', 10);
$Pdf->Cell(90, 70, "GRUA Y ESTACIONAMIENTO:");
$Pdf->Cell(60, 70, "" . $datos[0]["grua"]);
$Pdf->Cell(60, 70, "" . $datos[0]["cobertura_grua"]);
$Pdf->Ln(6);
$Pdf->SetFont('Arial', 'B', 10);
$Pdf->Cell(90, 70, "TOTAL GASTOS EXTRAS:");
$Pdf->Ln(6);
$Pdf->Cell(90, 65, "");
$Pdf->Cell(60, 65, "TOTAL A PAGAR: ");
$Pdf->Cell(60, 65, "" . $datos[0]["totalPagar"]);
$Pdf->SetFont('Arial', '', 7);
$Pdf->Cell(35, 20, "Fec. Hora Registro: ");
$Pdf->SetFont('Arial', 'B', 7);
$Pdf->Cell(90, 20, " / ");
$Pdf->ln(10);
$Pdf->SetTextColor(000);
$Pdf->SetFont("arial", "B", 10);
$Pdf->Output();