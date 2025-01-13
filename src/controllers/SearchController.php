<?php
require_once __DIR__ . '/../repositories/PolicyRepository.php';

class SearchController {
    private $policyRepository;
    private array $results = [];
    
    public function __construct($pdo) {
        $this->policyRepository = new PolicyRepository($pdo);
    }

    public function handleRequest() {
        $sery = isset($_GET['sery']) ? $_GET['sery'] : '';
        $number = isset($_GET['number']) ? $_GET['number'] : '';
        $uuid = isset($_GET['uuid']) ? $_GET['uuid'] : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

        $this->results = $this->policyRepository->search($sery, $number, $uuid, $page, $perPage);
    }

    public function displayResults() {
        if (empty($this->results)) {
            return;
        }

        echo "<table>";
        echo "<tr><th>ID</th><th>Sery</th><th>Number</th><th>End Date</th><th>UUID</th><th>Action</th></tr>";
        foreach ($this->results as $policy) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($policy->getId()) . "</td>";
            echo "<td>" . htmlspecialchars($policy->getSery()) . "</td>";
            echo "<td>" . htmlspecialchars($policy->getNumber()) . "</td>";
            echo "<td>" . htmlspecialchars($policy->getEndDate()) . "</td>";
            echo "<td>" . htmlspecialchars($policy->getUuid()) . "</td>";
            echo "<td><a href='index.php?action=download&uuid=" . urlencode($policy->getUuid()) . "'>Download</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    public function getResults(): array {
        return $this->results;
    }
}