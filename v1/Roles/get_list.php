<?php
require_once __DIR__."/../_Conexion/headers.php";
require_once __DIR__."/../_Conexion/config.php";
require_once __DIR__."/../_Conexion/respuesta.php";

$res = $connection->query("SELECT nombre FROM roles ORDER BY nombre");
$rows=[]; while($r=$res->fetch_assoc()) $rows[]=$r;
json_ok($rows);
