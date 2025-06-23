<?php
session_start();
require_once 'loadenv.php';

$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
$dbname = getenv('DB_NAME');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = ""; // Variable to store error messages

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password

    // Sanitize username
    $table_safe_username = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower($username));
    $table_name = "transactions_" . $table_safe_username;

    // Check if username already exists
    $check_user_sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($check_user_sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Email already taken. Please choose another.";
    } else {
        // Insert new user
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            $_SESSION['user'] = $username;
            $_SESSION['table_name'] = $table_name;

            // Create a user-specific transaction table
            $create_table_sql = "CREATE TABLE IF NOT EXISTS $table_name (
                id INT AUTO_INCREMENT PRIMARY KEY,
                date DATE NOT NULL,
                type ENUM('income', 'expense') NOT NULL,
                amount DECIMAL(10,2) NOT NULL,
                time TIME,
                comment VARCHAR(10) NULL  -- Add the comment column here
            )";

            if ($conn->query($create_table_sql) === TRUE) {
                // ===== Create "Users_IP" Database and Store IP Logs =====
                $conn->query("CREATE DATABASE IF NOT EXISTS Users_IP");
                $conn->select_db("Users_IP");

                // Create a user-specific login history table
                $ip_table_name = "ip_logs_" . $table_safe_username;
                $create_ip_table_sql = "CREATE TABLE IF NOT EXISTS $ip_table_name (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    ip_address VARCHAR(45) NOT NULL,
                    login_time DATETIME DEFAULT CURRENT_TIMESTAMP
                )";

                $conn->query($create_ip_table_sql);

                header("Location: login.php"); // Redirect after successful signup
                exit();
            } else {
                $error = "Error creating table: " . $conn->error;
            }
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
        <h2 class="text-2xl font-bold text-center mb-4">Sign Up</h2>

        <?php if (!empty($error)): ?>
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
            
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded w-full">Sign Up</button>
        </form>
        
        <p class="text-center mt-4">Already have an account? <a href="login.php" class="text-blue-500">Login</a></p>
    </div>

    <script src="../js/signup.js"></script>
</body>
</html>


