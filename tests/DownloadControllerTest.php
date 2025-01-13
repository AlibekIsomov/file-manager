<?php
require_once __DIR__ . '/../src/config/database.php';
require_once __DIR__ . '/../src/models/Policy.php';
require_once __DIR__ . '/../src/repositories/PolicyRepository.php';
require_once __DIR__ . '/../src/controllers/DownloadController.php';

class DownloadControllerTest {
    private $pdo;
    private $controller;

    public function __construct() {
        $host = '127.0.0.1';
        $port = '5432';
        $dbname = 'idkdb';
        $username = 'postgres';
        $password = 'y@suk321';

        $this->pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->controller = new DownloadController($this->pdo);
    }

    private function generatePdf($uuid) {
        $directory = __DIR__ . "/../policies/";
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        $filePath = $directory . $uuid . ".pdf";
        $content = "UUID: " . $uuid;
        file_put_contents($filePath, $content);
    }

    // ** If u have any database datas do not use this it's gonna clear the policies table and generetes random datas
    // private function insertPolicies() {
    //     // Drop the table if it exists
    //     $this->pdo->exec("DROP TABLE IF EXISTS policies");

    //     // Create the table
    //     $this->pdo->exec("CREATE TABLE policies (
    //         id SERIAL PRIMARY KEY,
    //         sery CHAR(1),
    //         number VARCHAR(6),
    //         end_date DATE,
    //         uuid VARCHAR(36)
    //     )");

    //     $stmt = $this->pdo->prepare("INSERT INTO policies (sery, number, end_date, uuid) VALUES (?, ?, ?, ?)");
    //     for ($i = 0; $i < 2000000; $i++) {
    //         $sery = chr(rand(65, 90));  // Random uppercase letter
    //         $number = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
    //         $end_date = date('Y-m-d', strtotime('+' . rand(1, 1825) . ' days'));
    //         $uuid = uniqid();
    //         $stmt->execute(array($sery, $number, $end_date, $uuid));
    //         $this->generatePdf($uuid);
            
    //         if ($i % 10000 == 0) {
    //             echo "Inserted $i records and generated $i PDF files\n";
    //         }
    //     }
    //     echo "Finished inserting 2 million records and generating 2 million PDF files\n";
    // }

    private function testDownload() {
        $stmt = $this->pdo->query("SELECT uuid FROM policies");
        $uuids = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $count = 0;
        foreach ($uuids as $uuid) {
            $filePath = __DIR__ . "/../policies/" . $uuid . ".pdf";
            assert(file_exists($filePath), "File not found: $filePath");
            $count++;
            if ($count % 10000 == 0) {
                echo "Checked $count PDF files\n";
            }
        }

        echo "All PDF files exist.\n";
    }

    public function runTests() {
        // $this->insertPolicies();
        $this->testDownload();
        echo "All tests passed!\n";
    }
}

$test = new DownloadControllerTest();
$test->runTests();