<?php

require_once 'src/config/Database.php';

$db_connection = new Database();
$conn = $db_connection->dbConnection();

$stmt=$conn->prepare("
CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `number_of_groups` int(11) NOT NULL,
  `student_per_group` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
$stmt->execute();

$stmt=$conn->prepare("

  ALTER TABLE `projects` ADD PRIMARY KEY(`id`);
  ");
$stmt->execute();


$stmt=$conn->prepare("

  ALTER TABLE `projects`MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  ");
$stmt->execute();


$stmt=$conn->prepare("
CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
$stmt->execute();


$stmt=$conn->prepare("

  ALTER TABLE `groups` ADD PRIMARY KEY(`id`);
  ");
$stmt->execute();

$stmt=$conn->prepare("

  ALTER TABLE `groups` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  ");
$stmt->execute();


$stmt=$conn->prepare("
CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
$stmt->execute();


$stmt=$conn->prepare("

  ALTER TABLE `students` ADD PRIMARY KEY(`id`);
  ");
$stmt->execute();


$stmt=$conn->prepare("

  ALTER TABLE `students`MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  ");
$stmt->execute();


