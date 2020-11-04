<?php

namespace UNIS\Db;

abstract class Table
{
	protected $table;
	protected $db;

	public function __construct(\Pdo $db)
	{
		$this->db = $db;
	}	

	public function add(array $data)
	{
		$columns = array_keys(self::prepareInsert($data));
		$values = array_values(self::prepareInsert($data));
	
		$query = "INSERT INTO {$this->table} (";
		$query .= implode(', ', $columns);
		$query .= ') VALUES (';
		$query .= implode(', ', $values) . ')';
		
		$stmt = $this->db->prepare($query);

		try {
			return $stmt->execute();
		} catch (\PDOException $e) {
			return $e->getMessage();
		}
	}

	public function fetchAll()
	{
		//busca todas as tarefas do banco
		$query = "SELECT * FROM {$this->table}";
		$stmt = $this->db->prepare($query);
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function find(int $id)
	{
		
	}

	public function update(array $data, int $id)
	{
		$columns = array_keys(self::prepareInsert($data));
		$values = array_values(self::prepareInsert($data));
		
		$resultado = array_combine($columns,$values);
		$query = "UPDATE {$this->table} SET ";
		$query .= "Titulo= ". $values[0].",";
		$query .= "Conteudo= ". $values[1].",";
		$query .= "Data= ". $values[2].",";
		$query .= "Prioridade= ". $values[3];
		$query .= " WHERE id =". $id;
		$stmt = $this->db->prepare($query);
		$stmt ->execute();
		
	}

	public function delete(int $id)
	{
		$query = "DELETE FROM {$this->table} WHERE ID = {$id}";
		$stmt = $this->db->prepare($query);
		$stmt->execute();
	}

	private function prepareInsert(array $data)
	{
		array_pop($data);

		array_walk($data, function(&$value) {
			$value = $this->db->quote($value);
		});
		
		return $data;
	}

}