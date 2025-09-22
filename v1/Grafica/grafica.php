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

$sql = "SELECT 
    (SELECT SUM(Monto) FROM entradas) +
    (SELECT SUM(Monto) FROM salidas) AS suma_total;";
    $result = $connection->query($sql); 

    if($result)
    {
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row);
    }
    else
    {
        echo "No hay registros";
    }


?>

