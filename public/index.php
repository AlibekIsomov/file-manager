<?php

require_once __DIR__ . '/../src/config/database.php';
require_once __DIR__ . '/../src/models/Policy.php';
require_once __DIR__ . '/../src/controllers/SearchController.php';
require_once __DIR__ . '/../src/controllers/DownloadController.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'search';

if ($action === 'search') {
    $controller = new SearchController($pdo);
    $controller->handleRequest();
} elseif ($action === 'download') {
    $controller = new DownloadController($pdo);
    $controller->handleRequest();
} else {
    echo "Invalid action";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Policy Search</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        form { margin-bottom: 20px; }
        input, button { margin: 5px; padding: 5px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Policy Search</h1>
    <form action="index.php" method="GET">
        <input type="hidden" name="action" value="search">
        <input type="text" name="sery" placeholder="Sery">
        <input type="text" name="number" placeholder="Number">
        <input type="text" name="uuid" placeholder="UUID">
        <button type="submit">Search</button>
    </form>
    <?php
    if ($action === 'search' && isset($controller)) {
        $controller->displayResults();
    }
    ?>
</body>
</html>

