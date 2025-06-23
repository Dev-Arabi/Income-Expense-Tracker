<?php
session_start();
require_once 'loadenv.php';

$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
$dbname = getenv('DB_NAME'); // Default database

$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the database exists, if not, create it
$db_check = $conn->query("SHOW DATABASES LIKE '$dbname'");

if ($db_check->num_rows == 0) {
    // Create the database
    $conn->query("CREATE DATABASE $dbname");
    $conn->select_db($dbname);

    // Create users table
    $conn->query("CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    )");

    // Create default transactions table (for admin use, user-specific tables will be created later)
    $conn->query("CREATE TABLE transactions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        date DATE NOT NULL,
        type ENUM('income', 'expense') NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        time TIME,
        comment VARCHAR(10) NULL  -- Add the comment column here
    )");
} else {
    // Select the database if it already exists
    $conn->select_db($dbname);
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $username;

        // Generate a safe table name for user transactions
        $table_safe_username = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($username));
        $_SESSION['table_name'] = "transactions_" . $table_safe_username;

        // ===== Improved IP Address Detection =====
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $user_ip = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
        } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $user_ip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $user_ip = $_SERVER['REMOTE_ADDR'];
        }

        // Connect to "Users_IP" database
        $conn->query("CREATE DATABASE IF NOT EXISTS Users_IP");
        $conn->select_db("Users_IP");

        // Create a user-specific IP log table
        $ip_table_name = "ip_logs_" . $table_safe_username;
        $create_ip_table_sql = "CREATE TABLE IF NOT EXISTS $ip_table_name (
            id INT AUTO_INCREMENT PRIMARY KEY,
            ip_address VARCHAR(45) NOT NULL,
            login_time DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
        $conn->query($create_ip_table_sql);

        // Insert login record
        $insert_ip_sql = "INSERT INTO $ip_table_name (ip_address) VALUES (?)";
        $stmt = $conn->prepare($insert_ip_sql);
        $stmt->bind_param("s", $user_ip);
        $stmt->execute();

        header("Location: ../index.php"); // Redirect to main page
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="../js/tailwind.js"></script>
    
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/67b0c5a3b09408190f0fb258/1ik5881og';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
    </script>
    <!--End of Tawk.to Script-->

</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-6 rounded shadow-md w-96">
        <h2 class="text-2xl font-bold text-center mb-4">Login</h2>
        
        <?php if ($error): ?>
            <p class="text-red-500 text-center"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <input type="email" name="username" placeholder="Email" required class="w-full p-2 border rounded">
            
            <div class="relative">
                <input type="password" name="password" id="password" placeholder="Password" required class="w-full p-2 border rounded pr-20">
                
                <!-- Custom Toggle Button -->
                <label class="absolute right-2 top-2 inline-flex cursor-pointer items-center">
                    <input id="toggle-password" class="peer sr-only" type="checkbox" />
                    <div class="border-gray-500 shadow-lg peer-checked:shadow-green-600 shadow-red-600 border flex h-6 w-12 items-center outline-none rounded bg-red-600 pl-7 text-white transition-all duration-300 peer-checked:bg-green-600 peer-checked:pl-2 peer-focus:outline-none"></div>
                    <svg class="peer-checked:opacity-0 transition-all duration-500 opacity-100 absolute left-6 stroke-gray-900 w-5 h-5" height="100" viewBox="0 0 100 100" width="100" xmlns="http://www.w3.org/2000/svg">
                        <path class="svg-fill-primary" d="M50,18A19.9,19.9,0,0,0,30,38v8a8,8,0,0,0-8,8V74a8,8,0,0,0,8,8H70a8,8,0,0,0,8-8V54a8,8,0,0,0-8-8H38V38a12,12,0,0,1,23.6-3,4,4,0,1,0,7.8-2A20.1,20.1,0,0,0,50,18Z"></path>
                    </svg>
                    <svg class="absolute transition-all duration-500 peer-checked:opacity-100 opacity-0 left-1 stroke-gray-900 w-5 h-5" height="100" viewBox="0 0 100 100" width="100" xmlns="http://www.w3.org/2000/svg">
                        <path d="M30,46V38a20,20,0,0,1,40,0v8a8,8,0,0,1,8,8V74a8,8,0,0,1-8,8H30a8,8,0,0,1-8-8V54A8,8,0,0,1,30,46Zm32-8v8H38V38a12,12,0,0,1,24,0Z" fill-rule="evenodd"></path>
                    </svg>
                    <div class="absolute left-1 top-1 flex h-3.5 w-4 items-center justify-center rounded-sm bg-white shadow-lg transition-all duration-300 peer-checked:left-7"></div>
                </label>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full">Login</button>
        </form>

        <p class="text-center mt-4">Don't have an account? 
            <a href="signup.php" class="text-blue-500">Sign Up</a>
        </p>
    </div>

    <script src="../js/login.js"></script>
</body>
</html>
