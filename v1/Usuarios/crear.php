<?php
require_once __DIR__."/../_Conexion/headers.php";
require_once __DIR__."/../_Conexion/config.php";
require_once __DIR__."/../_Conexion/respuesta.php";

$in = json_decode(file_get_contents('php://input'), true);
$nombre = trim($in['nombre'] ?? '');
$email  = trim($in['email'] ?? '');
$pass   = $in['password'] ?? '';
$rol    = $in['RolNombre'] ?? null;

if ($nombre==='' || $email==='' || $pass==='') json_error("Faltan campos", 422);

// validar duplicado
$st = $connection->prepare("SELECT id FROM Usuarios WHERE email=? LIMIT 1");
$st->bind_param("s",$email);
$st->execute(); $st->store_result();
if($st->num_rows>0) json_error("Email ya existe", 422);

// hash bcrypt compatible con AppPHP
$hash = password_hash($pass, PASSWORD_BCRYPT);

$st = $connection->prepare("INSERT INTO Usuarios (nombre,email,password,RolNombre,createdAt,updatedAt) VALUES (?,?,?,?,NOW(),NOW())");
$st->bind_param("ssss", $nombre,$email,$hash,$rol);
$ok = $st->execute();
if(!$ok) json_error("No se pudo crear", 500);

json_ok(["id"=>$st->insert_id], 201);
