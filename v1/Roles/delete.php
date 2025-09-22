<?php
require_once __DIR__."/../_Conexion/headers.php";
require_once __DIR__."/../_Conexion/config.php";
require_once __DIR__."/../_Conexion/respuesta.php";

$nombre = $_GET['nombre'] ?? '';
if($nombre==='') json_error("nombre requerido", 422);

$st = $connection->prepare("DELETE FROM roles WHERE nombre=?");
$st->bind_param("s",$nombre);
$ok = $st->execute();
if(!$ok) json_error("No se pudo eliminar (¿referencias?)", 500);
json_ok(["deleted"=>true]);
