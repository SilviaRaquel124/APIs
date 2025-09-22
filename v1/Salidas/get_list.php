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





    $sql = "SELECT * FROM salidas";
    $result = $connection->query($sql); 

    if($result)
    {
        $data = array();
        while($row = mysqli_fetch_assoc($result))
        {
            $data[] = $row;
        }
        echo json_encode($data);
    }
    else
    {
        echo "No hay registros";
    }
