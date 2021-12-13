<?php

$allow_method = 'DELETE';

require_once '../../functions.php';
require_once '../headers.php';
require_once '../../src/config/Database.php';
require_once '../../src/Student.php';

$db_connection = new Database();
$conn = $db_connection->dbConnection();
$student = new Student($conn);

$data = json_decode(file_get_contents("php://input"));

if (empty($data->id)) {
    http_response_code(400);
    echo json_encode(['message' => 'id is required']);
    exit;
}

if (!$student->read($data->id)) {
    http_response_code(404);
    echo json_encode(['message' => 'Record Not Found']);
    exit;
}

if (!$student->delete($data->id)) {
    http_response_code(500);
    echo  json_encode(['message' => 'Something went wrong']);
    exit;
}

http_response_code(201);
echo  json_encode(['message' => 'Student Deleted']);
exit;