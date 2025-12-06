<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "abbott_tuitions";

echo "<h2>Admin Setup & Diagnostic Tool</h2>";
echo "<hr>";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("<p style='color: red;'>❌ Connection failed: " . $conn->connect_error . "</p>");
}
echo "<p style='color: green;'>✅ Database connection successful!</p>";

// Check if admin_users table exists
$table_check = $conn->query("SHOW TABLES LIKE 'admin_users'");
if ($table_check->num_rows == 0) {
    echo "<p style='color: red;'>❌ Table 'admin_users' does not exist. Creating it now...</p>";
    
    $create_table = "CREATE TABLE IF NOT EXISTS admin_users (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        full_name VARCHAR(100) NOT NULL
    )";
    
    if ($conn->query($create_table)) {
        echo "<p style='color: green;'>✅ Table 'admin_users' created successfully!</p>";
    } else {
        die("<p style='color: red;'>❌ Error creating table: " . $conn->error . "</p>");
    }
} else {
    echo "<p style='color: green;'>✅ Table 'admin_users' exists!</p>";
}

// Check existing admin users
echo "<h3>Current Admin Users:</h3>";
$result = $conn->query("SELECT id, username, full_name FROM admin_users");
if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Username</th><th>Full Name</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['id']}</td><td>{$row['username']}</td><td>{$row['full_name']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p>No admin users found in database.</p>";
}

// Handle form submission to create new admin
if (isset($_POST['create_admin'])) {
    $new_username = $conn->real_escape_string($_POST['new_username']);
    $new_password = $_POST['new_password'];
    $new_fullname = $conn->real_escape_string($_POST['new_fullname']);
    
    // Check if username already exists
    $check = $conn->query("SELECT * FROM admin_users WHERE username = '$new_username'");
    
    if ($check->num_rows > 0) {
        echo "<p style='color: orange;'>⚠️ Username '$new_username' already exists. Updating password...</p>";
        
        // Update existing user
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $update_sql = "UPDATE admin_users SET password_hash = '$password_hash', full_name = '$new_fullname' WHERE username = '$new_username'";
        
        if ($conn->query($update_sql)) {
            echo "<p style='color: green;'>✅ Admin user updated successfully!</p>";
            echo "<p><strong>Username:</strong> $new_username</p>";
            echo "<p><strong>Password:</strong> $new_password</p>";
        } else {
            echo "<p style='color: red;'>❌ Error updating user: " . $conn->error . "</p>";
        }
    } else {
        // Create new user
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        
        $insert_sql = "INSERT INTO admin_users (username, password_hash, full_name) 
                       VALUES ('$new_username', '$password_hash', '$new_fullname')";
        
        if ($conn->query($insert_sql)) {
            echo "<p style='color: green;'>✅ Admin user created successfully!</p>";
            echo "<p><strong>Username:</strong> $new_username</p>";
            echo "<p><strong>Password:</strong> $new_password</p>";
        } else {
            echo "<p style='color: red;'>❌ Error creating user: " . $conn->error . "</p>";
        }
    }
    
    echo "<br><a href='admin.php' style='background: #FF6600; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Admin Login</a>";
}

// Handle delete admin
if (isset($_GET['delete_admin'])) {
    $delete_id = intval($_GET['delete_admin']);
    $conn->query("DELETE FROM admin_users WHERE id = $delete_id");
    echo "<p style='color: green;'>✅ Admin user deleted!</p>";
    echo "<meta http-equiv='refresh' content='1'>";
}

// Test password verification
if (isset($_POST['test_login'])) {
    $test_username = $conn->real_escape_string($_POST['test_username']);
    $test_password = $_POST['test_password'];
    
    echo "<h3>Login Test Results:</h3>";
    
    $sql = "SELECT * FROM admin_users WHERE username = '$test_username'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<p>✅ Username found in database</p>";
        echo "<p><strong>Stored Hash:</strong> " . substr($row['password_hash'], 0, 50) . "...</p>";
        
        if (password_verify($test_password, $row['password_hash'])) {
            echo "<p style='color: green; font-size: 18px;'><strong>✅ PASSWORD CORRECT! Login should work!</strong></p>";
            echo "<br><a href='admin.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Admin Login Now</a>";
        } else {
            echo "<p style='color: red; font-size: 18px;'><strong>❌ PASSWORD INCORRECT!</strong></p>";
            echo "<p>The password you entered does not match the stored hash.</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Username not found in database</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Setup Tool</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        .form-section {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        input, button {
            padding: 10px;
            margin: 5px 0;
            width: 100%;
            max-width: 300px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background: #FF6600;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background: #e55a00;
        }
        .test-btn {
            background: #17a2b8;
        }
        .test-btn:hover {
            background: #138496;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            max-width: 600px;
            margin: 10px 0;
        }
        th, td {
            text-align: left;
            padding: 10px;
        }
        th {
            background: #FF6600;
            color: white;
        }
    </style>
</head>
<body>

    <div class="form-section">
        <h3>📝 Create/Update Admin User</h3>
        <form method="POST">
            <label>Username:</label><br>
            <input type="text" name="new_username" required placeholder="Enter username" value="admin"><br>
            
            <label>Password:</label><br>
            <input type="text" name="new_password" required placeholder="Enter password" value="admin123"><br>
            
            <label>Full Name:</label><br>
            <input type="text" name="new_fullname" required placeholder="Enter full name" value="Administrator"><br>
            
            <button type="submit" name="create_admin">Create/Update Admin</button>
        </form>
    </div>

    <div class="form-section">
        <h3>🔐 Test Login Credentials</h3>
        <form method="POST">
            <label>Username:</label><br>
            <input type="text" name="test_username" required placeholder="Enter username to test" value="admin"><br>
            
            <label>Password:</label><br>
            <input type="text" name="test_password" required placeholder="Enter password to test" value="admin123"><br>
            
            <button type="submit" name="test_login" class="test-btn">Test Login</button>
        </form>
    </div>

    <div class="form-section">
        <h3>ℹ️ Instructions</h3>
        <ol>
            <li>First, create an admin user using the form above</li>
            <li>Then, test the login credentials to verify they work</li>
            <li>If the test is successful, go to the admin panel and login</li>
            <li><strong>Delete this file (setup_admin_fix.php) after setup for security!</strong></li>
        </ol>
    </div>

</body>
</html>