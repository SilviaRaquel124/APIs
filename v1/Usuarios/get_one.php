<?php
require_once __DIR__."/../_Conexion/headers.php";
require_once __DIR__."/../_Conexion/config.php";
require_once __DIR__."/../_Conexion/respuesta.php";

$id = (int)($_GET['id'] ?? 0);
if($id<=0) json_error("id inválido", 422);

$st = $connection->prepare("SELECT id,nombre,email,RolNombre,createdAt,updatedAt FROM Usuarios WHERE id=?");
$st->bind_param("i",$id);
$st->execute();
$r = $st->get_result()->fetch_assoc();
if(!$r) json_error("No encontrado", 404);
json_ok($r);
