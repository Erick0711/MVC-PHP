<?php
namespace App\Controllers;

class Controller
{
    public function view($route, $data = [])
    {
        // DESTRUTURAR EL ARRAY
        extract($data);
        
        $route = str_replace('.','/',$route); // PRUEBA/PRUEBA

        if(file_exists("../resources/views/{$route}.php")){
            ob_start();
            include("../resources/views/{$route}.php");
            $content = ob_get_clean();
            return $content;
        }else{
            echo "404 Not Found";
        }
    }
}
?>