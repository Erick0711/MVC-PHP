<?php

namespace App\Models;
use mysqli;
use PDO;
use PDOException;
use PDORow;

class Model
{
    protected $db_host = DB_HOST;
    protected $db_user = DB_USER;
    protected $db_pass = DB_PASSWORD;
    protected $db_name = DB_NAME;
    protected $db_port = DB_PORT;
    
    protected $connection;
    protected $query;
    protected $table;
    public function __construct()
    {
        $this->connection();
    }

    public function connection()
    {
        try{
            $this->connection = new PDO("mysql:host={$this->db_host};
                port={$this->db_port};
                dbname={$this->db_name}",
                $this->db_user,
                $this->db_pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $err){
            echo "FALLO LA CONEXION: {$err}";
        }
    }
    public function query($sql)
    {
        $this->query = $this->connection->prepare($sql);
        $this->query->execute();
        return $this;
    }
    public function first()
    {
        return $this->query->fetch(PDO::FETCH_ASSOC);
    }
    public function get()
    {
        return $this->query->fetchAll(PDO::FETCH_ASSOC);
    }

    // * CONSULTAS

    // BUSCA TODOS LOS DATOS
    public function all()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->query($sql)->get();
    }
    // BUSCA UN DATO ESPECIFICO
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = $id";
        return $this->query($sql)->first();
    }
    public function where($column, $operator, $value = null)
    {
    if($value == null){
        $value = $operator;
        $operator = '=';
    }
        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} '{$value}'";
        $this->query($sql);
        return $this;
    }
    public function create($data)
    {
        // $column = array_keys($data);
        // $column = implode(',', $column);
        // $value = array_values($data);
        // $value = "'". implode("', '", $value)."'";

        $column = [];
        $values = [];
        foreach($data as $key => $value)
        {
            $column[] = "{$key}";
            $values[] = "{$value}";
        }
        $column = implode(', ', $column);
        $values = "'".implode("', '",  $values)."'";
        $sql = "INSERT INTO {$this->table} ({$column}) VALUES ({$values})";
        $this->query($sql);

        $last_id = $this->connection->lastInsertId();
        return $this->find($last_id);
    }
    public function update($id, $data)
    {

        $fields = [];
        foreach($data as $key => $value)
        {
            $fields[] = "{$key} = '{$value}'";
        }
        $fields = implode(', ', $fields);
        $sql = "UPDATE {$this->table} SET {$fields} WHERE id = {$id}";
        $this->query($sql);
        return $this->find($id);
    }
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = {$id}";
        $this->query($sql);
    }
}

?>
