<?php
require_once __DIR__ . '/database.php';

$stmt = $pdo->prepare("INSERT INTO policies (sery, number, end_date, uuid) VALUES (?, ?, ?, ?)");

for ($i = 0; $i < 2000000; $i++) {
    $sery = chr(rand(65, 90));  // Random uppercase letter
    $number = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
    $end_date = date('Y-m-d', strtotime('+' . rand(1, 1825) . ' days'));
    $uuid = uniqid();
    
    $stmt->execute(array($sery, $number, $end_date, $uuid));
    
    if ($i % 10000 == 0) {
        echo "Inserted $i records\n";
    }
}

echo "Finished inserting 2 million records\n";