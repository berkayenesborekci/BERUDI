<?php
require_once "config.php";

$username = "admin";
$password = "admin123";
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$check_sql = "SELECT id FROM users WHERE username = ?";
if($check_stmt = mysqli_prepare($conn, $check_sql)) {
    mysqli_stmt_bind_param($check_stmt, "s", $username);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);
    
    if(mysqli_num_rows($result) > 0) {
        $update_sql = "UPDATE users SET password = ?, is_admin = 1 WHERE username = ?";
        if($update_stmt = mysqli_prepare($conn, $update_sql)) {
            mysqli_stmt_bind_param($update_stmt, "ss", $hashed_password, $username);
            if(mysqli_stmt_execute($update_stmt)) {
                echo "<h2>✅ Admin user updated successfully!</h2>";
                echo "<p><strong>Username:</strong> admin</p>";
                echo "<p><strong>Password:</strong> admin123</p>";
                echo "<p><strong>Status:</strong> Admin ✅</p>";
            } else {
                echo "<h2>❌ Error updating admin user</h2>";
            }
            mysqli_stmt_close($update_stmt);
        }
    } else {
        $insert_sql = "INSERT INTO users (username, password, is_admin) VALUES (?, ?, 1)";
        if($insert_stmt = mysqli_prepare($conn, $insert_sql)) {
            mysqli_stmt_bind_param($insert_stmt, "ss", $username, $hashed_password);
            if(mysqli_stmt_execute($insert_stmt)) {
                echo "<h2>✅ Admin user created successfully!</h2>";
                echo "<p><strong>Username:</strong> admin</p>";
                echo "<p><strong>Password:</strong> admin123</p>";
                echo "<p><strong>Status:</strong> Admin ✅</p>";
            } else {
                echo "<h2>❌ Error creating admin user</h2>";
            }
            mysqli_stmt_close($insert_stmt);
        }
    }
    mysqli_stmt_close($check_stmt);
}

echo "<hr>";
echo "<h3>All Users in Database:</h3>";
$all_users_sql = "SELECT id, username, is_admin FROM users";
$all_users_result = mysqli_query($conn, $all_users_sql);

if($all_users_result && mysqli_num_rows($all_users_result) > 0) {
    echo "<table border='1' style='border-collapse: collapse; padding: 5px;'>";
    echo "<tr><th>ID</th><th>Username</th><th>Admin Status</th></tr>";
    while($user = mysqli_fetch_assoc($all_users_result)) {
        $admin_status = $user['is_admin'] ? '✅ Admin' : '👤 User';
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . htmlspecialchars($user['username']) . "</td>";
        echo "<td>" . $admin_status . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No users found in database.</p>";
}

echo "<hr>";
echo "<h3>Login Links:</h3>";
echo "<p><a href='login.php'>🔐 Normal Login</a></p>";
echo "<p><a href='admin/'>⚙️ Admin Panel</a></p>";
echo "<p><a href='test_login.php'>🧪 Test Login</a></p>";
?> 