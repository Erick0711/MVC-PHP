<?php
namespace App\Models;
use PDO;
use PDOException;

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
    protected $primaryKey;
    protected $indices;
    protected $sql, $data = [], $params = null;
    // * CONSTRUCTOR DE LA CONEXION
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
    public function query($sql, $data = [], $params = null)
    {

        if(empty($data))
        {
            $this->query = $this->connection->prepare($sql);
            $this->query->execute();
            return $this;
        }else{
            $stmt = $this->connection->prepare($sql);
            $i = 1;
            foreach($data as $value)
            {
                $params = $i + $params;
                $stmt->bindValue($params, $value);
            }
            $stmt->execute();
            $this->query = $stmt;
        }
        return $this;
    }
    public function first()
    {
        if(empty($this->query))
        {
            $this->query($this->sql, $this->data, $this->params);
        }
        return $this->query->fetch(PDO::FETCH_ASSOC);
    }
    public function get()
    {
        if(empty($this->query))
        {
            $this->query($this->sql, $this->data, $this->params);
        }
        return $this->query->fetchAll(PDO::FETCH_ASSOC);
    }
    public function paginate($cant = 15)
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        if($this->sql){
            $sql = $this->sql . "LIMIT " . ($page - 1) * $cant . ",{$cant}";
            $data = $this->query($sql, $this->data, $this->params)->get();
        }else{
            $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {$this->table} LIMIT " . ($page - 1) * $cant . ",{$cant}";
            // $total = $this->query("SELECT count(*) as total FROM {$this->table}")->get()
            $data = $this->query($sql)->get();
        }


        $total = $this->query('SELECT FOUND_ROWS() AS total')->first()['total'];

        $uri = $_SERVER['REQUEST_URI'];
        $uri = trim($uri, '/');
        if(strpos($uri, '?')) // CUENTA LA POSICION
        {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }
        $last_page = ceil($total / $cant);
        return [
            'total' => $total,
            'from' => ($page - 1) * $cant + 1,
            'next_page' => $page < $last_page ? '/'. $uri . '?page=' . $page + 1 : null,
            'prev_page' => $page > 1 ? '/'. $uri . '?page=' . $page - 1 : null,
            'to' => ($page - 1) * $cant + count($data),
            'current_page' => $page,
            'last_page' => $last_page,
            'data' => $data
        ];
    }
    // * OBTENER SOLO DATOS ESPECIFICOS
    // TODO: TENER EN CUENTA SI EL PARAMETRO ES NULL ENTONSES SOLO RETORNARA LA CONSULTA
    public function select($data = null)
    {
        if($data === null)
        {
            $sql = "SELECT * FROM {$this->table}";
            return $sql;
        }else
        {
            $column = [];
            $values = [];
            foreach($data as $key => $value)
            {
                $column[] = "{$key}";
                $values[] = "{$value}";
            }
            $values = implode(', ', $values);
            $sql = "SELECT {$values} FROM {$this->table} ";
            $this->query($sql);
            return $this;
        }

    }
    // TODO: FUNCIONALIDAD JOIN TODAVIA NO COMPLETADA
    public function join($table)
    { 
        // $column = [];
        // $values = [];
        // foreach($this->indices as $key => $value)
        // {
        //     $column[] = "{$key}";
        //     $values[] = "{$value}";
        // }
        // $values = implode(', ', $values);
        $select = $this->select();
        $sql = $select."INNER JOIN {$table} ON {$this->table}.{$this->primaryKey} = {$table}";
        return $sql;
    }
    // * BUSCA TODOS LOS DATOS
    public function all()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->query($sql)->get();
    }
    // * BUSCA UN DATO ESPECIFICO POR EL ID COMO PARAMETRO
    public function find($id)
    {
        $sql = "SELECT  * FROM {$this->table} WHERE id = ?";
        return $this->query($sql,[$id])->first();
    }
    // * BUSCA DATOS A PARTIR DE UN OPERADOR YA SEA (=, <>, <, >,) COMO LOS PARAMETROS $COLUMN, $OPERATOR, $VALUE
    public function where($column, $operator, $value = null)
    {

    if($value == null){
        $value = $operator;
        $operator = '=';
    }
    if(empty($this->sql))
    {
        $this->sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {$this->table} WHERE {$column} {$operator} ?";
    // * LOS VALORES SE MANDARAN EN FORMA DE ARREGLO YA QUE AL DESGLOSARLO CON FOREACH DE LA FUNCTION QUERY SE NECESITA SER UN ARREGLO 
        // $this->query($sql,[$value]);
        $this->data[] = $value;
    }else{
        $this->sql .= "AND {$column} {$operator} ?";
    // * LOS VALORES SE MANDARAN EN FORMA DE ARREGLO YA QUE AL DESGLOSARLO CON FOREACH DE LA FUNCTION QUERY SE NECESITA SER UN ARREGLO 
        // $this->query($sql,[$value]);
        $this->data[] = $value;
    }
        return $this;
    }
    // * INSERTA DATOS
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
        // $values = "'".implode("', '",  $values)."'";
        $values = array_values($data);
        $sql = "INSERT INTO {$this->table} ({$column}) VALUES (". str_repeat('?, ', count($values) -1) . "?)";
        // * EN ESTE CASO NO SE MANDAN EN FORMA DE ARREGLO YA QUE EL DATA VIENE EN FORMA DE ARREGLO
        $this->query($sql, $values);

        $last_id = $this->connection->lastInsertId();
        return $this->find($last_id);
    }
    // * EDITAR DATOS
    public function update($id, $data)
    {
        $fields = [];
        foreach($data as $key => $value)
        {
            $fields[] = "{$key} = ?";
        }
        $fields = implode(', ', $fields);
        
        $sql = "UPDATE {$this->table} SET {$fields} WHERE id = ?";
        $values = array_values($data);
        $values[] = $id;
        $this->query($sql, $values);
        return $this->find($id);
    }
    // * ELIMINAR DATO
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = {$id}";
        $this->query($sql);
    }
}

?>
