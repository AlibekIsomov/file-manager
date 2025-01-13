<?php
require_once __DIR__ . '/../repositories/PolicyRepository.php';

class DownloadController {
    private $policyRepository;

    public function __construct($pdo) {
        $this->policyRepository = new PolicyRepository($pdo);
    }

    public function handleRequest() {
        $uuid = isset($_GET['uuid']) ? $_GET['uuid'] : '';

        if (empty($uuid)) {
            die("No UUID provided");
        }

        $policy = $this->policyRepository->findByUuid($uuid);

        if (!$policy) {
            die("Policy not found");
        }

        $end_date = new DateTime($policy->getEndDate());
        $current_date = new DateTime();

        if ($current_date <= $end_date) {
            // Redirect to remote URL
            header("Location: http://localhost/remote?uuid=" . urlencode($uuid));
            exit;
        } else {
            // Look for file in local storage
            $file_path = __DIR__ . "/../../policies/" . $uuid . ".pdf";
            if (file_exists($file_path)) {
                header("Content-Type: application/pdf");
                header("Content-Disposition: attachment; filename=\"" . $uuid . ".pdf\"");
                readfile($file_path);
            } else {
                echo "File not found in storage.";
            }
        }
    }
}