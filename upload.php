<?php
include 'config.php'; // Database configuration

// AWS SDK for S3
require 'vendor/autoload.php';

use Aws\S3\S3Client;
$bucketName = 'mywebsite-bucket1';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $filename = basename($_FILES['file']['name']);
    $fileType = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $tempFilePath = $_FILES['file']['tmp_name'];

    // Initialize S3 client
    $s3 = new S3Client([
        'version' => 'latest',
        'region'  => 'ap-south-1',
    ]);

    try {
        // Upload file to S3
        $result = $s3->putObject([
            'Bucket' => $bucketName,
            'Key'    => $filename,
            'SourceFile' => $tempFilePath,
            'ACL'    => 'private', // Or 'public-read' if required
        ]);

        $s3Path = $result['ObjectURL'];

        // Save metadata to RDS
        $sql = "INSERT INTO files (filename, filetype, s3_path) VALUES ('$f>        if ($conn->query($sql)) {
            echo "File uploaded successfully: <a href='$s3Path'>$filename</>        } else {
            echo "Database error: " . $conn->error;
        }
    } catch (Exception $e) {
        echo "S3 upload error: " . $e->getMessage();
    }
}
?>