<?php
require_once __DIR__."/../_Conexion/headers.php";
require_once __DIR__."/../_Conexion/config.php";
require_once __DIR__."/../_Conexion/respuesta.php";

$res = $connection->query("SELECT id,nombre,email,RolNombre,createdAt,updatedAt FROM Usuarios ORDER BY id DESC");
$out = [];
while($row = $res->fetch_assoc()) $out[] = $row;
json_ok($out);
