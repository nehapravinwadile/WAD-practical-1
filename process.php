<?php
// Database connection parameters
$servername = "localhost";
$username = "root"; // Change this to your MySQL username
$password = ""; // Change this to your MySQL password
$dbname = "reg_db";   

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);   

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);     
}

// If the form is submitted (POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Hash the password for security
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert data into the database
    $stmt_insert = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt_insert->bind_param("sss", $username, $email, $password_hash);

    if ($stmt_insert->execute()) {
        echo "<h2>Registration Successful</h2>";
    } else {
        echo "Error: " . $stmt_insert->error;
    }

    $stmt_insert->close();
}

// Display all users data
echo "<h2>All Users Data</h2>";
echo "<table border='1'>
        <tr>
            <th>Sr No</th>
            <th>Username</th>
            <th>Email</th>
            <th>Password</th>
        </tr>";

$result = $conn->query("SELECT * FROM users");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['srNo']}</td>
                <td>{$row['username']}</td>
                <td>{$row['email']}</td>
                <td>{$row['password']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No users found</td></tr>";
}

echo "</table>";

$conn->close();
?>
