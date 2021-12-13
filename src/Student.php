<?php

class Student
{
    private $connection;

    private $id;
    private $fullName;
    private $groupId;

    public function __construct($connection)
    {
        $this->connection = $connection;

    }

    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
        return $this;
    }

    public function setGroupId($groupId)
    {
        $this->groupId = $groupId;
        return $this;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getFullName() : string
    {
        return $this->fullName;
    }

    public function getGroupId() : ?int
    {
        return $this->groupId;
    }

    public function create() : ?Student
    {
        $sql = "INSERT INTO students (full_name, group_id) VALUES (:full_name, :group_id)";

        $stmt = $this->connection->prepare($sql);

        if (!$stmt->execute(['full_name' => $this->fullName, 'group_id' => $this->groupId])) {
            return null;
        }

        $this->id = $this->connection->lastInsertId();
        return $this;
    }

    public function read($id = null)
    {
        $sql = $id
            ? "SELECT * FROM students WHERE id='$id'"
            : "SELECT * FROM students ORDER BY id ASC";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute();

        return $id ? $stmt->fetch() : $stmt->fetchall();
    }

    public function updateGroup($data)
    {
        $update_query = "UPDATE `students` SET group_id = :groupID 
        WHERE id = :id";

        $update_stmt = $this->connection->prepare($update_query);

        $update_stmt->bindValue(':groupID', htmlspecialchars(strip_tags($this->groupId)), PDO::PARAM_STR);
        $update_stmt->bindValue(':id', $data, PDO::PARAM_INT);
        return $update_stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM students WHERE id = :id";
        $stmt = $this->connection->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }

    public function isNameOpen($student) : bool 
    {
        $sql= " SELECT COUNT(*) AS cnt 
                FROM students 
                WHERE full_name = :fullName
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['fullName' => $student->fullName]);
        $data = $stmt->fetch();

        if($data['cnt']==0){
            return true;
        }

        return false;

    }

    public function isStudentAllowedToBeAddedToGroup(int $groupId) : bool
    {
        $sql = "
            SELECT COUNT(*) AS cnt, projects.student_per_group 
            FROM students
            INNER JOIN groups ON students.group_id = groups.id
            INNER JOIN projects ON groups.project_id = projects.id
            WHERE group_id = :group_id
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['group_id' => $groupId]);
        $data = $stmt->fetch();

        if (empty($data['cnt']) || empty($data['student_per_group'])) {
            return true;
        }

        return $data['cnt'] < $data['student_per_group'];
    }
}