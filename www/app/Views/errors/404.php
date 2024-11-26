<?php
$title = "Error";
ob_start();
?>

    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1 class="display-4">Error 404</h1>
        </div>
    </div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
