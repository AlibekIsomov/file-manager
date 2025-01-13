<?php
require_once __DIR__ . '/../src/config/database.php';
require_once __DIR__ . '/../src/models/Policy.php';
require_once __DIR__ . '/../src/controllers/SearchController.php';
require_once __DIR__ . '/../src/controllers/DownloadController.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'search';

if ($action === 'search') {
    $controller = new SearchController($pdo);
    $controller->handleRequest();
    $results = $controller->getResults();
    $totalPages = ceil($results['total'] / $results['perPage']);
    $currentPage = $results['page'];
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
        .pagination { display: flex; gap: 10px; align-items: center; justify-content: center; margin: 20px 0; }
        .pagination a, .pagination span { padding: 8px 12px; border: 1px solid #ddd; text-decoration: none; }
        .pagination .active { background: #007bff; color: white; }
        .pagination .disabled { color: #ccc; pointer-events: none; }
        .per-page { margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Policy Search</h1>
        
        <form method="GET" action="index.php">
            <input type="hidden" name="action" value="search">
            <div class="form-group">
                <input type="text" name="sery" placeholder="Sery" value="<?= htmlspecialchars($_GET['sery'] ?? '') ?>">
                <input type="text" name="number" placeholder="Number" value="<?= htmlspecialchars($_GET['number'] ?? '') ?>">
                <input type="text" name="uuid" placeholder="UUID" value="<?= htmlspecialchars($_GET['uuid'] ?? '') ?>">
                <button type="submit">Search</button>
            </div>
        </form>

        <?php if (isset($results) && !empty($results['policies'])): ?>
            <div class="per-page">
                Show per page: 
                <select name="per_page" onchange="this.form.submit()">
                    <option value="10" <?= $results['perPage'] == 10 ? 'selected' : '' ?>>10</option>
                    <option value="25" <?= $results['perPage'] == 25 ? 'selected' : '' ?>>25</option>
                    <option value="50" <?= $results['perPage'] == 50 ? 'selected' : '' ?>>50</option>
                    <option value="100" <?= $results['perPage'] == 100 ? 'selected' : '' ?>>100</option>
                </select>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Sery</th>
                        <th>Number</th>
                        <th>UUID</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results['policies'] as $policy): ?>
                        <tr>
                            <td><?= htmlspecialchars($policy->getSery()) ?></td>
                            <td><?= htmlspecialchars($policy->getNumber()) ?></td>
                            <td><?= htmlspecialchars($policy->getUuid()) ?></td>
                            <td>
                                <a href="index.php?action=download&id=<?= $policy->getId() ?>">Download</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php
                    $currentPage = $results['page'];
                    $maxPages = 5;
                    $startPage = max(1, $currentPage - floor($maxPages / 2));
                    $endPage = min($totalPages, $startPage + $maxPages - 1);
                    
                    // First & Previous
                    $prevPage = max(1, $currentPage - 1);
                    ?>
                    <a href="?action=search&page=1&sery=<?= urlencode($_GET['sery'] ?? '') ?>&number=<?= urlencode($_GET['number'] ?? '') ?>&uuid=<?= urlencode($_GET['uuid'] ?? '') ?>&per_page=<?= $results['perPage'] ?>" class="<?= $currentPage == 1 ? 'disabled' : '' ?>">First</a>
                    <a href="?action=search&page=<?= $prevPage ?>&sery=<?= urlencode($_GET['sery'] ?? '') ?>&number=<?= urlencode($_GET['number'] ?? '') ?>&uuid=<?= urlencode($_GET['uuid'] ?? '') ?>&per_page=<?= $results['perPage'] ?>" class="<?= $currentPage == 1 ? 'disabled' : '' ?>">Previous</a>

                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <a href="?action=search&page=<?= $i ?>&sery=<?= urlencode($_GET['sery'] ?? '') ?>&number=<?= urlencode($_GET['number'] ?? '') ?>&uuid=<?= urlencode($_GET['uuid'] ?? '') ?>&per_page=<?= $results['perPage'] ?>" 
                           class="<?= $i === $currentPage ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>

                    <?php
                    // Next & Last
                    $nextPage = min($totalPages, $currentPage + 1);
                    ?>
                    <a href="?action=search&page=<?= $nextPage ?>&sery=<?= urlencode($_GET['sery'] ?? '') ?>&number=<?= urlencode($_GET['number'] ?? '') ?>&uuid=<?= urlencode($_GET['uuid'] ?? '') ?>&per_page=<?= $results['perPage'] ?>" class="<?= $currentPage == $totalPages ? 'disabled' : '' ?>">Next</a>
                    <a href="?action=search&page=<?= $totalPages ?>&sery=<?= urlencode($_GET['sery'] ?? '') ?>&number=<?= urlencode($_GET['number'] ?? '') ?>&uuid=<?= urlencode($_GET['uuid'] ?? '') ?>&per_page=<?= $results['perPage'] ?>" class="<?= $currentPage == $totalPages ? 'disabled' : '' ?>">Last</a>
                    
                    <span>Page <?= $currentPage ?> of <?= $totalPages ?> (Total: <?= $results['total'] ?>)</span>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>