<?php

class Group
{
    private $connection;

    private $id;
    private $projectID;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function create(int $projectId)
    {
        $sql = "INSERT INTO groups (project_id) VALUES(:project_id)";

        $insert_stmt = $this->connection->prepare($sql);
        $insert_stmt->execute(['project_id' => $projectId]);

        return $this->connection->lastInsertId();
    }

    public function getAll(int $projectId) : ?array
    {
        $sql = "SELECT id FROM groups WHERE project_id = :project_id";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['project_id' => $projectId]);

        return array_column($stmt->fetchAll(), 'id');
    }

      public function read() : ?array
    {
        $sql = "SELECT * FROM groups ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return array_column($stmt->fetchAll(), 'id');
    }
}