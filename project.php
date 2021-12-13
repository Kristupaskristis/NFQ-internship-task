<?php

require_once 'functions.php';
require_once 'src/config/Database.php';
require_once 'src/Project.php';
require_once 'src/Group.php';

$op = $_POST['op'];

switch ($op) {
    case 'create':
        if (empty($_POST['title']) || empty($_POST['number_of_groups']) || empty($_POST['student_per_group'])) {
            raise_msg('Fill all required fields!', MSG_ERROR);
            break;
        }

        if (!is_numeric($_POST['number_of_groups']) || $_POST['number_of_groups'] > MAX_GROUP_NUMBER) {
            raise_msg('Maximum groups allowed: ' . MAX_GROUP_NUMBER, MSG_ERROR);
            break;
        }

        $pdo = (new Database)->dbConnection();

        $project = (new Project($pdo))
            ->setTitle($_POST['title'])
            ->setNumberOfGroups($_POST['number_of_groups'])
            ->setStudentPerGroup($_POST['student_per_group'])
            ->create()
        ;

        if (!$project->getId()) {
            raise_msg('Failed to create project', MSG_ERROR);
            break;
        }

        for ($i = 1; $i <= $_POST['number_of_groups']; $i++) {
            if (!(new Group($pdo))->create($project->getId())) {
                raise_msg('Something went wrong!', MSG_ERROR);
                $project->delete();
                break;
            }
        }

        raise_msg('Project created successfully', MSG_SUCCESS);
        break;
    default:
        raise_msg('Something went wrong!', MSG_ERROR);
}

header('Location: index.php');
exit;