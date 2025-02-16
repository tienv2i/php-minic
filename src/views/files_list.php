<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploaded Files</title>
</head>
<body>
    <h1>Uploaded Files</h1>
    <form action="/files/upload" method="post" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <button type="submit">Upload</button>
    </form>

    <h2>File List:</h2>
    <ul>
        <?php foreach ($files as $file): ?>
            <li><a href="<?=uploadedFileUrl(htmlspecialchars($file)) ?>"><?= htmlspecialchars($file) ?></a></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
