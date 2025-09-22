<?php
require_once __DIR__."/../_Conexion/headers.php";
require_once __DIR__."/../_Conexion/config.php";
require_once __DIR__."/../_Conexion/respuesta.php";

$old = $_GET['old'] ?? '';
$in = json_decode(file_get_contents('php://input'), true);
$new = trim($in['nombre'] ?? '');
if($old===''||$new==='') json_error("nombres requeridos", 422);

$st = $connection->prepare("UPDATE roles SET nombre=? WHERE nombre=?");
$st->bind_param("ss",$new,$old);
$ok = $st->execute();
if(!$ok) json_error("No se pudo actualizar", 500);
json_ok(["updated"=>true]);
