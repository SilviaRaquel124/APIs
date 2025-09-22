<?php
include("conexion.php");
session_start();

header("Content-Type: application/json");

$tipo = $_POST['tipo_entrada'] ?? '';
$monto = $_POST['monto'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$id_usuario = $_SESSION['id_usuario'] ?? null;

if (!$tipo || !$monto || !$fecha || !$id_usuario) {
    echo json_encode(["error" => "Datos incompletos"]);
    exit;
}

$ruta = null;
if (isset($_FILES["factura"]) && $_FILES["factura"]["error"] == 0) {
    $carpeta = "uploads/";
    $permitidos = ['application/pdf', 'image/jpeg', 'image/png'];
    $maxSize = 2 * 1024 * 1024; // 2MB

    if (!in_array($_FILES["factura"]["type"], $permitidos)) {
        echo json_encode(["error" => "Formato de archivo no permitido"]);
        exit;
    }
    if ($_FILES["factura"]["size"] > $maxSize) {
        echo json_encode(["error" => "El archivo excede el tamaño máximo permitido (2MB)"]);
        exit;
    }
    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0777, true);
    }
    $nombreArchivo = time() . "_" . basename($_FILES["factura"]["name"]);
    $ruta = $carpeta . $nombreArchivo;
    if (!move_uploaded_file($_FILES["factura"]["tmp_name"], $ruta)) {
        echo json_encode(["error" => "Error al subir el archivo"]);
        exit;
    }
}

$stmt = $conexion->prepare("INSERT INTO entradas (Tipo_entrada, Monto, Fecha, Factura, id_usuario) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sdssi", $tipo, $monto, $fecha, $ruta, $id_usuario);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "id_entrada" => $conexion->insert_id]);
} else {
    echo json_encode(["error" => $stmt->error]);
}
$stmt->close();
?>