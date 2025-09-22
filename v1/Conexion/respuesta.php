<?php
function json_ok($data=[], $code=200){
  http_response_code($code);
  header("Content-Type: application/json; charset=utf-8");
  echo json_encode(["ok"=>true, "data"=>$data], JSON_UNESCAPED_UNICODE);
  exit;
}
function json_error($msg="Error", $code=400){
  http_response_code($code);
  header("Content-Type: application/json; charset=utf-8");
  echo json_encode(["ok"=>false, "error"=>$msg], JSON_UNESCAPED_UNICODE);
  exit;
}
