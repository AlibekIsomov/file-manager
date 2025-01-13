<?php
require_once __DIR__ . '/../models/Policy.php';

class PolicyRepository {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function search($sery, $number, $uuid) {
        $query = "SELECT * FROM policies WHERE 1=1";
        $params = array();
    
        if (!empty($sery)) {
            $query .= " AND sery ILIKE ?";
            $params[] = "%$sery%";
        }
        if (!empty($number)) {
            $query .= " AND number ILIKE ?";
            $params[] = "%$number%";
        }
        if (!empty($uuid)) {
            $query .= " AND uuid ILIKE ?";
            $params[] = "%$uuid%";
        }
    
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $policies = array();
        foreach ($results as $row) {
            $policies[] = new Policy(
                $row['id'],
                $row['sery'],
                $row['number'],
                $row['end_date'],
                $row['uuid']
            );
        }
    
        return $policies;
    }
}

