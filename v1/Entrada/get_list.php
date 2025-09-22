<?php
require_once __DIR__."/../Conexion/headers.php";
require_once __DIR__."/../Conexion/config.php";
require_once __DIR__."/../Conexion/respuesta.php";

$idUsuario = isset($_GET['usuarioId']) ? (int)$_GET['usuarioId'] : null;
$limit     = isset($_GET['limit'])  ? max(1, min((int)$_GET['limit'], 100)) : 50;
$offset    = isset($_GET['offset']) ? max(0, (int)$_GET['offset']) : 0;

// 1) Conteo total (para meta)
if ($idUsuario) {
  $stc = $connection->prepare("SELECT COUNT(*) as total FROM entradas WHERE usuarioId=?");
  $stc->bind_param("i", $idUsuario);
} else {
  $stc = $connection->prepare("SELECT COUNT(*) as total FROM entradas");
}
$stc->execute();
$total = (int)$stc->get_result()->fetch_assoc()['total'];

// 2) Query con filtros y paginado
if ($idUsuario) {
  $sql = "SELECT id, tipo, monto, fecha, facturaRuta, usuarioId
          FROM entradas
          WHERE usuarioId=?
          ORDER BY Fecha DESC, id DESC
          LIMIT ? OFFSET ?";
  $st = $connection->prepare($sql);
  $st->bind_param("iii", $idUsuario, $limit, $offset);
} else {
  $sql = "SELECT id, tipo, monto, fecha, facturaRuta, usuarioId
          FROM entradas
          ORDER BY Fecha DESC, id DESC
          LIMIT ? OFFSET ?";
  $st = $connection->prepare($sql);
  $st->bind_param("ii", $limit, $offset);
}

$st->execute();
$res = $st->get_result();

$rows = [];
while ($r = $res->fetch_assoc()) $rows[] = $r;

json_ok([
  "items" => $rows,
  "meta"  => ["total" => $total, "limit" => $limit, "offset" => $offset]
]);
