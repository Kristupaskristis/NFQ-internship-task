<?php

if ($_SERVER['REQUEST_METHOD'] !== $allow_method) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed', true, 405);
    exit;
}

header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: " . $allow_method);
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

?>