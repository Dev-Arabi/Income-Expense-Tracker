<?php
require 'loadenv.php'; // Load database credentials

$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
$dbname = getenv('DB_NAME');

// Define the directory and filename for the backup
$backup_dir = '/var/dbbackup'; // Define backup directory
$backup_file = $backup_dir . "/talidbbackup_" . date("Y-m-d_H-i-s") . ".sql"; // Backup file path

// Ensure the directory exists
if (!is_dir($backup_dir)) {
    mkdir($backup_dir, 0755, true); // Create the directory if it doesn't exist
}

// Create the database backup using mysqldump
$command = "mysqldump --user={$username} --host={$servername} {$dbname} > {$backup_file}";
putenv("MYSQL_PWD={$password}"); // Set password as an environment variable
exec($command);

// Check if the backup was created successfully
if (file_exists($backup_file)) {
    echo "Backup successfully created at: $backup_file";
} else {
    echo "Failed to create the backup.";
}
?>
