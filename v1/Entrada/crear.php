<?php
require_once __DIR__."/../Conexion/headers.php";
require_once __DIR__."/../Conexion/config.php";
require_once __DIR__."/../Conexion/respuesta.php";

// 1) Body: JSON o form-data
$raw = file_get_contents('php://input');
$in  = json_decode($raw, true);
if (!is_array($in) || empty($in)) {
  // fallback a form-data
  $in = $_POST;
}

// 2) Sanitizar / validar
$tipo       = trim($in['tipo'] ?? '');
$monto      = $in['monto'] ?? null;
$fecha      = trim($in['fecha'] ?? '');      // 'YYYY-MM-DD'
$factura    = trim($in['facturaRuta'] ?? null);  // puede ser ruta o nombre
$idUsuario  = $in['usuarioId'] ?? null;

if ($tipo === '' || !is_numeric($monto) || $monto <= 0 || $fecha === '' || !is_numeric($idUsuario)) {
  json_error("Datos inválidos: tipo, Monto (>0), fecha, usuarioId", 422);
}

// 3) Insert preparado
// NOTA: ajusta nombres de columnas según tu tabla real.
// Aquí asumo: id (PK AI), tipo, monto (DECIMAL), fecha (DATE), facturaRuta (VARCHAR), usuarioId (INT)
$sql = "INSERT INTO entradas (tipo, monto, fecha, facturaRuta, usuarioId) 
        VALUES (?, ?, ?, ?, ?)";
$st  = $connection->prepare($sql);
if (!$st) json_error("Prepare failed", 500, $connection->error);

// tipos: s (string), d (double), s, s, i (int)
$st->bind_param("sdssi", $tipo, $monto, $fecha, $factura, $idUsuario);

$ok = $st->execute();
if (!$ok) json_error("No se pudo crear entrada", 500, $st->error);

json_ok(["id" => $st->insert_id], 201);