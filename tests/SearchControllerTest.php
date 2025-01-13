<?php
require_once __DIR__ . '/../repositories/PolicyRepository.php';

class SearchController {
    private $policyRepository;
    private $results;
    private $totalPages;
    private $currentPage;

    public function __construct($pdo) {
        $this->policyRepository = new PolicyRepository($pdo);
    }

    public function handleRequest() {
        $sery = isset($_GET['sery']) ? $_GET['sery'] : '';
        $number = isset($_GET['number']) ? $_GET['number'] : '';
        $uuid = isset($_GET['uuid']) ? $_GET['uuid'] : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $this->results = $this->policyRepository->search($sery, $number, $uuid, $limit, $offset);
        $totalResults = $this->policyRepository->countSearchResults($sery, $number, $uuid);
        $this->totalPages = ceil($totalResults / $limit);
        $this->currentPage = $page;

        $this->displayResults($sery, $number, $uuid);
    }

    public function displayResults($sery, $number, $uuid) {
        if (!isset($this->results)) {
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

        // Display pagination
        echo "<div>";
        for ($i = 1; $i <= $this->totalPages; $i++) {
            if ($i == $this->currentPage) {
                echo "<strong>$i</strong> ";
            } else {
                echo "<a href='?page=$i&sery=" . urlencode($sery) . "&number=" . urlencode($number) . "&uuid=" . urlencode($uuid) . "'>$i</a> ";
            }
        }
        echo "</div>";
    }
}