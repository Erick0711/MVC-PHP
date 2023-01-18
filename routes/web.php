<?php
use  Lib\Route;

Route::get('/', function () {
    echo 'Esto es la pagina prinsipal';
});

Route::get('/contact', function () {
    echo 'Esto es la pagina contacto';
});

Route::dispatch();
?>