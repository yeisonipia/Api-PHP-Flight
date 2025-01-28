<?php

declare(strict_types=1);

require 'flight/Flight.php';

Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=saferesidence', 'root', ''));

//lee los datos y los muestra a cualquier interface que los requiere
Flight::route('GET /usuarios', function () {
    $sentencia = Flight::db( )->prepare('select * from usuarios');
    $sentencia->execute();

    $datos = $sentencia->fetchAll();
    flight::json($datos);
});

//creamos los datos
Flight::route('POST /usuarios', function () {
    $nombres =(Flight::request()->data ->nombres);
    $apellidos =(Flight::request()->data ->apellidos);
    $cedula =(Flight::request()->data ->cedula);
    $telefono =(Flight::request()->data ->telefono);
    $usuario = Flight::request()->data ->usuario;
    $contraseña =(Flight::request()->data ->contraseña);
    $contraseña_hash = password_hash($contraseña, PASSWORD_BCRYPT);//contraseña encriptada
    $email = Flight::request()->data ->email;
    $id_rol = (int) Flight::request()->data ->id_rol;


    $sql = 'insert into usuarios(nombres, apellidos, cedula, telefono, usuario, contraseña,email, id_rol) values(?,?,?,?,?,?,?,?)';
    $sentencia = Flight::db()->prepare($sql);
    $sentencia->bindParam(1, $nombres);
    $sentencia->bindParam(2, $apellidos);
    $sentencia->bindParam(3, $cedula);
    $sentencia->bindParam(4, $telefono);
    $sentencia->bindParam(5, $usuario);
    $sentencia->bindParam(6, $contraseña_hash);
    $sentencia->bindParam(7, $email);
    $sentencia->bindParam(8, $id_rol);
    $sentencia->execute();

});

//borrar registro
Flight::route('DELETE /usuarios', function () {

    $id_usuario = Flight::request()->data->id_usuario;

    $sql='delete from usuarios where id_usuario = ?';
    $sentencia = Flight::db()->prepare($sql);
    $sentencia->bindParam(1, $id_usuario);
    $sentencia->execute();

});


//actualizamos datos

Flight::route('PUT /usuarios', function () {
    $sql = 'update usuarios set nombres=? where id_usuario = ?';

    $nombres = Flight::request()->data ->nombres;
    $id_usuario = Flight::request()->data ->id_usuario;

    $sentencia = Flight::db()->prepare($sql);
    $sentencia->bindParam(1, $nombres);
    $sentencia->bindParam(2, $id_usuario);
    $sentencia->execute();  
    
});

Flight::start();
