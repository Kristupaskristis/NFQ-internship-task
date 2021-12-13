<?php

require_once 'functions.php';
require_once 'src/config/Database.php';
require_once 'src/Project.php';
require_once 'src/Student.php';

$pdo = (new Database())->dbConnection();

$project = (new Project($pdo))->getLast();
$students = (new Student($pdo))->read();
$groups = (new Group($pdo))->read();

?>

<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<title>Page Title</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="assets/css/styles.css">
<body>

<div class="container">
    <?= get_mgs() ?>

    <?php if (!$project) : ?>
        <form action="project.php" method="post">
            <input type="hidden" name="op" value="create">

            <label>
                Project:
                <input type="text" name="title" required>
            </label>

            <label>
                Number of Groups:
                <input type="number" name="number_of_groups" max="<?= MAX_GROUP_NUMBER ?>" required>
            </label>

            <label>
                Student per Group:
                <input type="number" name="student_per_group" required>
            </label>

            <input type="submit" value="Create">
        </form>
    <?php else : ?>
        <div class="project-info">
            <div>Project: <?= $project->getTitle() ?></div>
            <div>Number of Groups: <?= $project->getNumberOfGroups() ?></div>
            <div>Students per Group: <?= $project->getStudentPerGroup() ?></div>
        </div>

        <h3>Students</h3>

        <table id="studentsForm">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Group</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$students) : ?>
                    <tr><td colspan="3">No students</td></tr>
                <?php else : ?>
                    <?php foreach ($students as $student) : ?>
                        <tr>
                            <td><?= $student['full_name'] ?></td>
                            <td><?= $student['group_id'] ? 'Group #' . $student['group_id'] : '-' ?></td>
                            <td>
                                <form action="student.php" method="post">
                                    <input type="hidden" name="op" value="delete">

                                    <a href="javascript:void(0)" onclick="deleteStudent($(this).data('id'))" data-id="<?= $student['id'] ?>">
                                        Delete
                                    </a>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
        <button id="addStudent">Add new student</button>

            <?php foreach ($groups as $group) : ?>
                <?php if ($group + 1 % 2 == 0) : ?> 
        <table class="groupLeft" >
            <?php else : ?>
               <table class="groupRight">
               <?php endif ?>
            <thead>
                <tr>
                    <th><?= $group ? 'Group #' . $group : '' ?> </th>
                </tr>
            </thead>
             <?php foreach($students as $student) : ?>
                <?php if ($group == $student["group_id"]) : ?>
            <tbody>
                <td><?= $student["full_name"] ?></td>
            </tbody>
        <?php endif ?> 
            <?php endforeach ?>
             <?php endforeach ?>
             
        </table>

<?php endif ?>
               




</div>

<script>
    var groups = '<?= json_encode($project->getGroups()) ?>';
</script>

<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>
