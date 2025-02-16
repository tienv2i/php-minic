<?php if (!defined('BASE_PATH')) die('Access denied!'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=$static_url?>/bootstrap.min.css">
    <title><?=$site_title ?? 'Homepage'?></title>
</head>
<body>
    <h1 class="text-center">Welcome to php-minic!</h1>
    <main class="container">
        <h2>File list:</h2>
        <p class="px-4">
            [List upload file here]
        </p>
    </main>

    <script src="<?=$static_url?>/bootstrap.bundle.min.js"></script>
</body>
</html>
