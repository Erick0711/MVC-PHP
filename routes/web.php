<?php
use  Lib\Route;

Route::get('/', function () {
    return [
        'title' => 'Home',
        'content' => 'Hello Pint'
    ];
});

Route::get('/contact', function () {
    return 'Esto es la pagina contacto';
});


Route::get('/producto/:id/:programacion', function ($id, $programacion) {
    return 'Esto es la pagina  id:'.$id. "numero programacion:".$programacion;
});


Route::get('/producto/prueba', function () {
    return 'Esto es la producto prueba';
});

Route::dispatch();
?>