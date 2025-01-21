<?php
require_once __DIR__ . '/database.php';

$stmt = $pdo->prepare("INSERT INTO policies (sery, number, end_date, uuid) VALUES (?, ?, ?, ?)");

$pdo->beginTransaction();  // Start transaction for better performance

for ($i = 0; $i < 2000000; $i++) {
    $sery = chr(rand(65, 90));  // Random uppercase letter
    $number = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
    
    // Random date logic using strtotime() (works on PHP 5.3)
    $end_date = date('Y-m-d', strtotime('+' . rand(1, 1825) . ' days')); // Random date within 5 years
    
    // Using uniqid() for unique identifier
    $uuid = uniqid();

    // Execute prepared statement
    $stmt->execute(array($sery, $number, $end_date, $uuid));

    // Echo progress every 10,000 records
    if ($i % 10000 == 0) {
        echo "Inserted $i records\n";
    }
}

$pdo->commit();  // Commit the transaction

echo "Finished inserting 2 million records\n";
