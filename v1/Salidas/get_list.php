<?php
require_once __DIR__."/../Conexion/headers.php";
require_once __DIR__."/../Conexion/config.php";
require_once __DIR__."/../Conexion/respuesta.php";

/**
 * Filtros y paginación opcionales:
 *   ?id_usuario=6&limit=20&offset=0
 */
$idUsuario = isset($_GET['usuarioId']) ? (int)$_GET['usuarioId'] : null;
$limit     = isset($_GET['limit'])  ? max(1, min((int)$_GET['limit'], 100)) : 50;
$offset    = isset($_GET['offset']) ? max(0, (int)$_GET['offset']) : 0;

// --- total para meta ---
if ($idUsuario) {
  $stc = $connection->prepare("SELECT COUNT(*) AS total FROM salidas WHERE usuarioId=?");
  $stc->bind_param("i", $idUsuario);
} else {
  $stc = $connection->prepare("SELECT COUNT(*) AS total FROM salidas");
}
$stc->execute();
$total = (int)$stc->get_result()->fetch_assoc()['total'];

// --- consulta de datos ---
if ($idUsuario) {
  $sql = "SELECT id, tipo, monto, fecha, facturaRuta, usuarioId
          FROM salidas
          WHERE usuarioId=?
          ORDER BY fecha DESC, id DESC
          LIMIT ? OFFSET ?";
  $st = $connection->prepare($sql);
  $st->bind_param("iii", $idUsuario, $limit, $offset);
} else {
  $sql = "SELECT id, tipo, monto, fecha, facturaRuta, usuarioId
          FROM salidas
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
  "meta"  => ["total"=>$total, "limit"=>$limit, "offset"=>$offset]
]);
