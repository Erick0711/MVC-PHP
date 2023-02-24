<?php 
namespace App\Models;
class Contact extends Model
{
    protected $table = 'contact';
    protected $primaryKey = 'id';
    protected $indices = ['contact_id'];
    // ?  SI SE DESEA CONECTARSE A LA OTRA BD SE DEBE REEMPLAZAR LOS SIGUENTES ATRIBUTOS DE LA CLASE
    // protected $db_host = DB_HOST;
    // protected $db_user = DB_USER;
    // protected $db_pass = DB_PASSWORD;
    // protected $db_name = DB_NAME;
    // protected $db_port = DB_PORT;
}

?>