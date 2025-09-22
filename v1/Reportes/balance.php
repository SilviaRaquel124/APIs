<?php
require_once __DIR__."/../_Conexion/headers.php";
require_once __DIR__."/../_Conexion/config.php";
require_once __DIR__."/../_Conexion/respuesta.php";

$uid = (int)($_GET['usuarioId'] ?? 1);

$st = $connection->prepare("SELECT COALESCE(SUM(monto),0) total FROM Entradas WHERE usuarioId=?");
$st->bind_param("i",$uid); $st->execute();
$e = (float)$st->get_result()->fetch_assoc()['total'];

$st = $connection->prepare("SELECT COALESCE(SUM(monto),0) total FROM Salidas WHERE usuarioId=?");
$st->bind_param("i",$uid); $st->execute();
$s = (float)$st->get_result()->fetch_assoc()['total'];

json_ok(["entradas"=>$e,"salidas"=>$s,"balance"=>$e-$s]);
