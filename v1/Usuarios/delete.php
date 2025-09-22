<?php
require_once __DIR__."/../_Conexion/headers.php";
require_once __DIR__."/../_Conexion/config.php";
require_once __DIR__."/../_Conexion/respuesta.php";

$id = (int)($_GET['id'] ?? 0);
if($id<=0) json_error("id inválido", 422);

$st = $connection->prepare("DELETE FROM Usuarios WHERE id=?");
$st->bind_param("i",$id);
$ok = $st->execute();
if(!$ok) json_error("No se pudo eliminar", 500);
json_ok(["deleted"=>true]);
