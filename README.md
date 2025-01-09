# AWS_File_Storage_Website


 
# Dynamic Web Application for Storing Files with Text and Documents on AWS

## Overview
This project is a dynamic web application that allows users to upload files (e.g., images, PDFs) along with text descriptions. Uploaded files are stored in an AWS S3 bucket, and metadata (file name, description, file URL) is saved in an Amazon RDS MySQL database. The application is built using PHP and hosted on an EC2 instance.

---

## Features
- Upload files and text descriptions via a web interface.
- Store files securely in an AWS S3 bucket.
- Save file metadata in an RDS MySQL database.
- Scalable and secure architecture leveraging AWS services.

---

## Prerequisites
1. **AWS Account**: Ensure you have access to AWS services (S3, RDS, EC2).
2. **Development Environment**:
   - PHP 7 or higher.
   - Composer (PHP dependency manager).
   - AWS CLI for managing AWS resources.
3. **Knowledge Requirements**:
   - Basic understanding of PHP and AWS services.

---

## Architecture
### AWS Services Used:
1. **S3**: For storing uploaded files.
2. **RDS**: For storing metadata in a MySQL database.
3. **EC2**: For hosting the web application.
4. **IAM Roles**: For secure interaction between EC2, S3, and RDS.

---

## Setup and Implementation

### 1. AWS Configuration
#### a. **S3 Bucket**
1. Create a new bucket with a unique name.
2. Enable versioning if required.
3. Set permissions (e.g., private or public access).

#### b. **RDS MySQL Database**
1. Create an RDS instance with MySQL.
2. Configure the database name and credentials.
3. Set security group rules to allow EC2 access.

#### c. **EC2 Instance**
1. Launch an EC2 instance (Amazon Linux 2 or Ubuntu).
2. Configure security groups to allow HTTP traffic on port 80.
3. Attach an IAM role with S3 and RDS access permissions.

### 2. Install LAMP Stack on EC2
1. Update the instance and install necessary packages:
   ```bash
   sudo yum update -y
   sudo yum install httpd php php-mysqlnd -y
   ```
2. Start and enable Apache:
   ```bash
   sudo systemctl start httpd
   sudo systemctl enable httpd
   ```

### 3. Install AWS SDK for PHP
1. Install Composer:
   ```bash
   curl -sS https://getcomposer.org/installer | php
   sudo mv composer.phar /usr/local/bin/composer
   ```
2. Install AWS SDK:
   ```bash
   composer require aws/aws-sdk-php
   ```

### 4. Database Setup
Create a table in the RDS MySQL database:
```sql
CREATE DATABASE file_storage;
USE file_storage;

CREATE TABLE files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    description TEXT,
    file_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 5. PHP Application
#### File Upload Form:
Create an `index.php` file for the web interface:
```html
<form action="upload.php" method="post" enctype="multipart/form-data">
    <label for="file">Choose a file:</label>
    <input type="file" name="file" id="file" required><br><br>
    <label for="description">Description:</label>
    <textarea name="description" id="description" required></textarea><br><br>
    <button type="submit" name="upload">Upload</button>
</form>
```

#### File Upload Script:
Create an `upload.php` file to handle file uploads:
```php
<?php
require 'vendor/autoload.php';
use Aws\S3\S3Client;

if (isset($_POST['upload'])) {
    $description = $_POST['description'];
    $file = $_FILES['file'];

    // AWS S3 configuration
    $s3Client = new S3Client([
        'region' => 'us-west-2',
        'version' => 'latest',
    ]);
    $bucket = 'your-s3-bucket-name';
    $key = 'uploads/' . basename($file['name']);

    // Upload file to S3
    try {
        $result = $s3Client->putObject([
            'Bucket' => $bucket,
            'Key'    => $key,
            'SourceFile' => $file['tmp_name'],
            'ACL'    => 'public-read',
        ]);
        
        $fileUrl = $result['ObjectURL'];

        // Save metadata to MySQL
        $conn = new mysqli('your-db-endpoint', 'username', 'password', 'file_storage');
        $stmt = $conn->prepare("INSERT INTO files (name, description, file_url) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $file['name'], $description, $fileUrl);
        $stmt->execute();
        $stmt->close();

        echo "File uploaded successfully!";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
```

---

## Testing
1. Open the EC2 public IP address in a browser.
2. Use the form to upload a file and description.
3. Verify the file is uploaded to S3 and metadata is saved in the RDS database.

---

## Security Considerations
1. Use IAM roles instead of hardcoding credentials.
2. Enable encryption for S3 buckets and RDS.
3. Implement input validation and user authentication.

---

## Future Enhancements
1. Add user authentication for secure access.
2. Implement a front-end framework for improved UI/UX.
3. Add file versioning and tagging for better organization.

---

## Conclusion
This project demonstrates the integration of AWS services (S3, RDS, EC2) with PHP to create a secure, scalable, and dynamic web application for file and metadata storage.
