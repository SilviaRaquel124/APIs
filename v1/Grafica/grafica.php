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
   (SELECT COALESCE(SUM(Monto), 0) FROM entradas) AS total_entradas,
    (SELECT COALESCE(SUM(Monto), 0) FROM salidas) AS total_salidas,
    (SELECT COALESCE(SUM(Monto), 0) FROM entradas) +
    (SELECT COALESCE(SUM(Monto), 0) FROM salidas) AS suma_total";

$result = $connection->query($sql); 

if($result && $result->num_rows > 0)
{
    $row = mysqli_fetch_assoc($result);
    echo json_encode($row);
}
else
{
    echo json_encode(array(
        "total_entradas" => 0,
        "total_salidas" => 0,
        "suma_total" => 0
    ));
}




?>

