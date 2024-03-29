<?php
require_once("cls_db.php");

abstract class cls_claseVehiculo extends cls_db
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
						"res" => "El nombre de la clase de vehículo no puede estar vacío",
						"code" => 400
					],
					"code" => 400
				];
			}

			$result = $this->SearchByNombre($this->nombre);

			if (isset($result)) {
				return [
					"data" => [
						"res" => "Este nombre de clase vehículo ($this->nombre) ya existe",
						"code" => 400
					],
					"code" => 400
				];
			}

			$sql = $this->db->prepare("INSERT INTO clasevehiculo(clase_nombre, clase_estatus) VALUES(?, 1)");
			$sql->execute([$this->nombre]);

			$this->id = $this->db->lastInsertId();

			if ($sql->rowCount() > 0) {
				$this->reg_bitacora([
					'table_name' => "clasevehiculo",
					'des' => "Registro de nueva clase de vehiculo ($this->nombre)"
				]);

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
						"res" => "Estas duplicando los datos de otra claseVehiculo"
					],
					"code" => 400
				];
			}
			$sql = $this->db->prepare("UPDATE clasevehiculo SET
            clase_nombre = ? WHERE claseVehiculo_id = ?");
			if ($sql->execute([$this->nombre, $this->id])) {
				$this->reg_bitacora([
					'table_name' => "clasevehiculo",
					'des' => "Actualización clase de vehiculo ($this->nombre)"
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
		$sql = $this->db->prepare("SELECT * FROM clasevehiculo WHERE 
        clase_nombre =? AND claseVehiculo_id = ?");
		if ($sql->execute([$this->nombre, $this->id]))
			$resultado = $sql->fetch(PDO::FETCH_ASSOC);
		else
			$resultado = [];
		return $resultado;
	}

	protected function Delete()
	{
		try {
			$sql = $this->db->prepare("UPDATE clasevehiculo SET clase_estatus = ? WHERE claseVehiculo_id = ?");
			if ($sql->execute([$this->estatus, $this->id])) {
				$this->reg_bitacora([
					'table_name' => "clasevehiculo",
					'des' => "Eliminación clase de vehiculo ($this->id)"
				]);

				return [
					"data" => [
						"res" => "Estatus modificado"
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
		$sql = $this->db->prepare("SELECT * FROM clasevehiculo WHERE claseVehiculo_id = ?");
		if ($sql->execute([$id]))
			$resultado = $sql->fetch(PDO::FETCH_ASSOC);
		else
			$resultado = [];
		return $resultado;
	}

	protected function SearchByNombre($nombre)
	{
		$sql = $this->db->prepare("SELECT * FROM clasevehiculo WHERE clase_nombre = ?");
		if ($sql->execute([$this->nombre]))
			$resultado = $sql->fetch(PDO::FETCH_ASSOC);
		else
			$resultado = [];
		return $resultado;
	}

	protected function GetAll()
	{
		$sql = $this->db->prepare("SELECT * FROM clasevehiculo ORDER BY claseVehiculo_id DESC");
		if ($sql->execute())
			$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
		else
			$resultado = [];
		return $resultado;
	}
}
