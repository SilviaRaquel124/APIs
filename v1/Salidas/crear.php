<?php

define("HOSTNAME", "localhost");
define("USERNAME", "root"); 
define("PASSWORD", "");
define("DATABASE", "api");

$connection = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE);

if (!$connection) {
    die("Connection failed: ");
}
else {
    // echo "Connected successfully";
}

header("Content-Type: application/json");



$datos = json_decode(file_get_contents('php://input'), true);
    $Tipo_salida = $datos['Tipo_salida'];
    $Monto = $datos['Monto'];
    $Fecha = $datos['Fecha'];
    $Factura = $datos['Factura'];
    $id_usuario = $datos['id_usuario'];

    $sql = "INSERT INTO salidas (Tipo_salida, Monto, Fecha, Factura, id_usuario) VALUES ('$Tipo_salida', '$Monto', '$Fecha', '$Factura', '$id_usuario')";
    $result = $connection->query($sql);

   if($result){ 
    $dato['id_salidas'] = $connection->insert_id; 
    echo json_encode($dato); 
    }else{ 
    echo json_encode(array('error'=>'Error al crear usuario', 'sql_error' => $connection->error));
    }



?>
