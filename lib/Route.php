<?php

namespace Lib;

class Route
{
    private static $routes = [];

    public static function get($uri, $callback)
    {
        $uri = trim($uri, '/'); // SE ENCARGA DE ELIMINAR EL SLASH / TANTO AL AL PRINSIPIO O AL FINAL /contact = > contact
        self::$routes['GET'][$uri] = $callback;
    }
    public static function post($uri, $callback)
    {
        $uri = trim($uri, '/');
        self::$routes['POST'][$uri] = $callback;
    }

    public static function dispatch()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $uri = trim($uri, '/');

        if(strpos($uri, '?')) // CUENTA LA POSICION
        {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }

        $method = $_SERVER['REQUEST_METHOD'];
        foreach (self::$routes[$method] as $route => $callback) {
            if(strpos($route, ':') !== false) 
            {
                $route = preg_replace('#:[a-zA-Z0-9]+#', '([a-zA-Z0-9]+)', $route);
                // echo $route;
                // return;
            }

            if(preg_match("#^$route$#", $uri, $matches)) // ENCONTRADA LA SIMILITUD CON UN COMODIN DE EXPRESION REGULAR DONDE TIENE QUE INICIAR Y TERMINAR CON ESA URL SINO ENVIARA UN 404
            {
                
                $params = array_slice($matches, 1);
                // $response = $callback(...$params); // RECUPERA LA FUNCION Y LA EJECUTA
                if (is_callable($callback)) 
                {
                    $response = $callback(...$params);
                }
                if (is_array($callback)) 
                {
                    $controllers = new $callback[0];
                    $response = $controllers->{$callback[1]}(...$params);
                }
                if(is_array($response) || is_object($response)){
                    header('content-type: application/json');
                    echo json_encode($response);
                }else{
                    echo $response;
                }
                // echo json_encode($params);
                return;
            }
        }
        echo "404";
    }
}
