<?php 
namespace App\Models;

use mysqli;

class Contact
{
    protected $db_host = DB_HOST;
    protected $db_user = DB_USER;
    protected $db_pass = DB_PASSWORD;
    protected $db_name = DB_NAME;
    protected $db_port = DB_PORT;
    
    protected $connection;

    public function __construct()
    {
        $this->connection();
    }

    public function connection()
    {
        $this->connection = new mysqli($this->db_host, $this->db_user, $this->db_pass,$this->db_name, $this->db_port);
        if ($this->connection->error) {
            die('Error connecting to' . $this->connection->error);
        }
    }
}

?>