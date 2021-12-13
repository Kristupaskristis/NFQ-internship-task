<?php

require_once 'Group.php';

class Project
{
    private $connection;

    private int $id;
    private string $title;
    private int $numberOfGroups;
    private array $groups;
    private int $studentPerGroup;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    public function setNumberOfGroups($numberOfGroups)
    {
        $this->numberOfGroups = $numberOfGroups;
        return $this;
    }

    public function setStudentPerGroup($studentPerGroup)
    {
        $this->studentPerGroup = $studentPerGroup;
        return $this;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function getNumberOfGroups() : int
    {
        return $this->numberOfGroups;
    }

    public function getStudentPerGroup() : int
    {
        return $this->studentPerGroup;
    }

    public function getGroups() : array
    {
        return $this->groups;
    }

    public function create()
    {
        $insert_query = "INSERT INTO projects (title,number_of_groups,student_per_group) VALUES(:title,:numberOfGroups,:studentPerGroup)";

        $insert_stmt = $this->connection->prepare($insert_query);

        $insert_stmt->bindValue(':title', htmlspecialchars(strip_tags($this->title)), PDO::PARAM_STR);
        $insert_stmt->bindValue(':numberOfGroups', htmlspecialchars(strip_tags($this->numberOfGroups)), PDO::PARAM_STR);
        $insert_stmt->bindValue(':studentPerGroup', htmlspecialchars(strip_tags($this->studentPerGroup)), PDO::PARAM_STR);

        $insert_stmt->execute();
        $id = $this->connection->lastInsertId();

        if (!$id) {
            return null;
        }

        $this->id = $id;
        return $this;
    }


    public function read($id = null)
    {
        $sql = $id ? "SELECT * FROM `projects` WHERE id='$id'" : "SELECT * FROM `projects` ORDER BY id DESC";
        $stmt = $this->connection->prepare($sql);

        return $stmt->execute();
    }

    public function delete(?int $id = null)
    {
        $id = $id ?? $this->id;

        if (!$id) {
            return null;
        }

        $sql = "DELETE FROM projects WHERE id = :id";

        $stmt = $this->connection->prepare($sql);
        return $stmt->execute(['id' => $this->id]);
    }

    public function getLast() : ?Project
    {
        $sql = "SELECT * FROM projects ORDER BY id DESC LIMIT 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $project = $stmt->fetch();

        if (!$project) {
            return null;
        }

        $this->id = $project['id'];
        $this->title = $project['title'];
        $this->numberOfGroups = $project['number_of_groups'];
        $this->groups = (new Group($this->connection))->getAll($this->id);
        $this->studentPerGroup = $project['student_per_group'];

        return $this;
    }
}