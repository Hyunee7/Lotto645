<?php
// === Configuration ===
$targetDir = __DIR__ . "/uploads/";
$maxFileSize = 50 * 1024 * 1024; // 50 MB limit — you can change it

// === Handle upload ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['file'])) {
        die("No file uploaded.");
    }

    $file = $_FILES['file'];
    $targetFile = $targetDir . basename($file["name"]);

    // Check file size
    if ($file["size"] > $maxFileSize) {
        die("File too large. Limit is " . ($maxFileSize / 1024 / 1024) . " MB.");
    }

    // Create folder if not exists
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Move uploaded file
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        echo "✅ Upload successful: " . htmlspecialchars($file["name"]);
    } else {
        echo "❌ Upload failed.";
    }
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>File Upload</title>
  <style>
    body { font-family: Arial; background: #0d1117; color: #fff; text-align: center; padding-top: 100px; }
    form { background: #161b22; padding: 40px; border-radius: 12px; display: inline-block; }
    input[type=file], input[type=submit] { margin: 10px; }
    input[type=submit] { background: #238636; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; }
    input[type=submit]:hover { background: #2ea043; }
  </style>
</head>
<body>
  <h2>🚀 Upload Your File</h2>
  <form action="" method="POST" enctype="multipart/form-data">
    <input type="file" name="file" required><br>
    <input type="submit" value="Upload File">
  </form>
</body>
</html>
