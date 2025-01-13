<?php
require_once __DIR__ . '/../models/Policy.php';

class PolicyRepository {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function search($sery, $number, $uuid, $page = 1, $perPage = 10) {
        // Count total results first
        $countQuery = "SELECT COUNT(*) as total FROM policies WHERE 1=1";
        $params = array();
    
        if (!empty($sery)) {
            $countQuery .= " AND sery ILIKE ?";
            $params[] = "%$sery%";
        }
        if (!empty($number)) {
            $countQuery .= " AND number ILIKE ?";
            $params[] = "%$number%";
        }
        if (!empty($uuid)) {
            $countQuery .= " AND uuid ILIKE ?";
            $params[] = "%$uuid%";
        }
        
        $stmt = $this->pdo->prepare($countQuery);
        $stmt->execute($params);
        $totalCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Main query with pagination
        $query = "SELECT * FROM policies WHERE 1=1";
        
        if (!empty($sery)) {
            $query .= " AND sery ILIKE ?";
        }
        if (!empty($number)) {
            $query .= " AND number ILIKE ?";
        }
        if (!empty($uuid)) {
            $query .= " AND uuid ILIKE ?";
        }
        
        $offset = ($page - 1) * $perPage;
        $query .= " LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;
        
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
        
        return [
            'total' => $totalCount,
            'page' => $page,
            'perPage' => $perPage,
            'policies' => $policies
        ];
    }
}