<?php 
$title = htmlspecialchars($menu['name']); 
ob_start();
?>

    <h1><?= htmlspecialchars($menu['name']) ?></h1>
    <p><?= htmlspecialchars($menu['description']) ?></p>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
