<?php

$allow_method = 'POST';

require_once '../../functions.php';
require_once '../headers.php';
require_once '../../src/config/Database.php';
require_once '../../src/Student.php';

$db_connection = new Database();
$conn = $db_connection->dbConnection();

$student = new Student($conn);

if ($_POST) {
    $data = (object) $_POST;

} else {
    $data = json_decode(file_get_contents("php://input"));
}

if (empty($data->fullName)) {
    http_response_code(400);
    echo json_encode(['name' => 'name', 'message' => 'Please enter fullname']);
    exit;
}
    
$student->setFullName($data->fullName);

if (!$student->isNameOpen($student)) {
    http_response_code(400);
    echo json_encode(['name' => 'name', 'message' => 'Name already taken']);
    exit;
}

if (!empty($data->groupId)) {
    if (!$student->isStudentAllowedToBeAddedToGroup($data->groupId)) {
        http_response_code(400);
        echo json_encode(['name' => 'group', 'message' => 'Group is full']);
        exit;
    }

    $student->setGroupId($data->groupId);
}


if (!$student->create()) {
    http_response_code(400);
    echo json_encode(['name' => 'name', 'meesage' => 'Data not Inserted']);
    exit;
}

http_response_code(201);

echo json_encode([
    'meesage' => 'Data Inserted Successfully',
    'data'    => [
        'id'       => $student->getId(),
        'name'     => $student->getFullName(),
        'group_id' => $student->getGroupId(),
    ],
]);

exit;
