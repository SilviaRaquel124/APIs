<?php
require_once __DIR__."/../_Conexion/headers.php";
require_once __DIR__."/../_Conexion/config.php";
require_once __DIR__."/../_Conexion/respuesta.php";

$in = json_decode(file_get_contents('php://input'), true);
$email = trim($in['email'] ?? '');
$pass  = $in['password'] ?? '';

if ($email===''||$pass==='') json_error("Email y password requeridos", 422);

$st = $connection->prepare("SELECT id,nombre,email,password,RolNombre FROM Usuarios WHERE email=? LIMIT 1");
$st->bind_param("s",$email);
$st->execute();
$r = $st->get_result()->fetch_assoc();

if(!$r || !password_verify($pass, $r['password'])) json_error("Credenciales inválidas", 401);

// Podrías emitir un JWT aquí si lo necesitas.
// Por ahora devolvemos datos del usuario:
unset($r['password']);
json_ok(["user"=>$r]);
