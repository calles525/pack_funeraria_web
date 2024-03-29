<?php

if (!class_exists('cls_db')) require_once("cls_db.php");

abstract class cls_transporte extends cls_db
{
	protected $id, $nombre, $estatus;

	public function __construct()
	{
		parent::__construct();
	}

	protected function Save()
	{
		try {
			if (empty($this->nombre)) {
				return [
					"data" => [
						"res" => "El nombre de la linea de transporte no puede estar vacío",
						"code" => 400
					],
					"code" => 400
				];
			}
			$result = $this->SearchByNombre($this->nombre);
			if (isset($result[0])) {
				return [
					"data" => [
						"res" => "Este nombre de transporte ($this->nombre) ya existe"
					],
					"code" => 400
				];
			}
			$sql = $this->db->prepare("INSERT INTO transporte(transporte_nombre,transporte_estatus) VALUES(?,1)");
			$sql->execute([$this->nombre]);
			$this->id = $this->db->lastInsertId();
			if ($sql->rowCount() > 0) {

				$this->reg_bitacora([
					'table_name' => "transporte",
					'des' => "Insert en transporte (nombre: $this->nombre)"
				]);

				return [
					"data" => [
						"res" => "Registro exitoso"
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

	protected function update()
	{
		try {
			$res = $this->GetDuplicados();
			if (isset($res[0])) {
				return [
					"data" => [
						"res" => "Estas duplicando los datos de otra transporte"
					],
					"code" => 400
				];
			}
			$sql = $this->db->prepare("UPDATE transporte SET
            transporte_nombre = ? WHERE transporte_id = ?");
			if ($sql->execute([$this->nombre, $this->id])) {

				$this->reg_bitacora([
					'table_name' => "transporte",
					'des' => "Actualización en transporte (id: $this->id, nombre: $this->nombre)"
				]);

				return [
					"data" => [
						"res" => "Actualización de datos exitosa"
					],
					"code" => 300
				];
			}
			return [
				"data" => [
					"res" => "Actualización de datos fallida"
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

	private function GetDuplicados()
	{
		$sql = $this->db->prepare("SELECT * FROM transporte WHERE 
        transporte_nombre =? AND transporte_id = ?");
		if ($sql->execute([$this->nombre, $this->id])) $resultado = $sql->fetch(PDO::FETCH_ASSOC);
		else $resultado = [];
		return $resultado;
	}

	protected function Delete()
	{
		try {
			$sql = $this->db->prepare("UPDATE transporte SET transporte_estatus = ? WHERE transporte_id = ?");
			if ($sql->execute([$this->estatus, $this->id])) {
				$this->reg_bitacora([
					'table_name' => "transporte",
					'des' => "Cambio de estatus en transporte (id: $this->id, estatus: $this->estatus)"
				]);

				return [
					"data" => [
						"res" => "transporte desactivada"
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
		$sql = $this->db->prepare("SELECT * FROM transporte WHERE transporte_id = ?");
		if ($sql->execute([$id])) $resultado = $sql->fetch(PDO::FETCH_ASSOC);
		else $resultado = [];
		return $resultado;
	}

	protected function SearchByNombre($nombre)
	{
		$sql = $this->db->prepare("SELECT * FROM transporte WHERE transporte_nombre = ?");
		if ($sql->execute([$this->nombre])) $resultado = $sql->fetch(PDO::FETCH_ASSOC);
		else $resultado = [];
		return $resultado;
	}

	protected function GetAll()
	{
		$sql = $this->db->prepare("SELECT * FROM transporte ORDER BY transporte_id DESC");
		if ($sql->execute()) $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
		else $resultado = [];
		return $resultado;
	}
}
