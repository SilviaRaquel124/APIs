<?php
define("HOSTNAME", "localhost");
define("USERNAME", "root");
define("PASSWORD", "");
define("DATABASE", "controlfinanzas"); // usa tu BD real

$connection = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE);
if (!$connection) {
  http_response_code(500);
  header('Content-Type: application/json');
  echo json_encode(["error"=>"DB connection failed"]);
  exit;
}
mysqli_set_charset($connection, "utf8mb4");
