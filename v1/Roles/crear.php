<?php
require_once __DIR__."/../_Conexion/headers.php";
require_once __DIR__."/../_Conexion/config.php";
require_once __DIR__."/../_Conexion/respuesta.php";

$in = json_decode(file_get_contents('php://input'), true);
$nombre = trim($in['nombre'] ?? '');
if($nombre==='') json_error("nombre requerido", 422);

$st = $connection->prepare("INSERT INTO roles(nombre) VALUES (?)");
$st->bind_param("s",$nombre);
$ok = $st->execute();
if(!$ok) json_error("No se pudo crear", 500);
json_ok(["nombre"=>$nombre], 201);
