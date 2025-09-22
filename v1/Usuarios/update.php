<?php
require_once __DIR__."/../_Conexion/headers.php";
require_once __DIR__."/../_Conexion/config.php";
require_once __DIR__."/../_Conexion/respuesta.php";

$id = (int)($_GET['id'] ?? 0);
if($id<=0) json_error("id inválido", 422);

$in = json_decode(file_get_contents('php://input'), true);
$sets = []; $types = ""; $vals = [];

foreach (['nombre','email','RolNombre'] as $f) {
  if (isset($in[$f])) { $sets[]="$f=?"; $types.="s"; $vals[]=$in[$f]; }
}
if (isset($in['password']) && $in['password']!=='') {
  $sets[] = "password=?"; $types.="s"; $vals[] = password_hash($in['password'], PASSWORD_BCRYPT);
}
if (!$sets) json_ok(["updated"=>false]);

$sql = "UPDATE Usuarios SET ".implode(',', $sets).", updatedAt=NOW() WHERE id=?";
$types .= "i"; $vals[] = $id;

$st = $connection->prepare($sql);
$st->bind_param($types, ...$vals);
$ok = $st->execute();
if(!$ok) json_error("No se pudo actualizar", 500);
json_ok(["updated"=>true]);
