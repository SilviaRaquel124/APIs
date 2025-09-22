<?php
require_once __DIR__."/../Conexion/headers.php";
require_once __DIR__."/../Conexion/config.php";
require_once __DIR__."/../Conexion/respuesta.php";

/**
 * Body puede venir en JSON (fetch) o form-data (POST clásico).
 */
$raw = file_get_contents('php://input');
$in  = json_decode($raw, true);
if (!is_array($in) || empty($in)) {
  // fallback a multipart/x-www-form-urlencoded
  $in = $_POST;
}

// --- Validaciones básicas ---
$tipo      = trim($in['tipo'] ?? '');
$monto     = $in['monto'] ?? null;          // número
$fecha     = trim($in['fecha'] ?? '');      // 'YYYY-MM-DD'
$factura   = trim($in['facturaRuta'] ?? null);  // opcional (ruta/archivo)
$idUsuario = $in['usuarioId'] ?? null;     // número

if ($tipo === '' || !is_numeric($monto) || $monto <= 0 || $fecha === '' || !is_numeric($idUsuario)) {
  json_error("Datos inválidos: tipo, monto (>0), fecha, usuarioId", 422);
}

// --- INSERT preparado ---
$sql = "INSERT INTO salidas (tipo, monto, fecha, factura, usuarioId)
        VALUES (?, ?, ?, ?, ?)";
$st  = $connection->prepare($sql);
if (!$st) json_error("Prepare failed", 500, $connection->error);

// tipos: s (string), d (double), s, s, i
$st->bind_param("sdssi", $tipo, $monto, $fecha, $factura, $idUsuario);

$ok = $st->execute();
if (!$ok) json_error("No se pudo crear salida", 500, $st->error);

json_ok(["id" => $st->insert_id], 201);
