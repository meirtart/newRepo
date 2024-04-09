<?php

session_start();
// Establish a database connection
$conn = mysqli_connect("localhost", "root", "", "project");

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get the form data
    $login_email = $_POST['email'];
    $login_password = $_POST['password'];

    // Assuming your column for email is named 'family_email'
    $sql = "SELECT * FROM family WHERE family_email = '$login_email' AND family_password = password('$login_password')";

    // Execute the query
    $result = mysqli_query($conn, $sql);

    // Check if a row is returned, meaning credentials are correct
    if (mysqli_num_rows($result) == 1) {


        $row = mysqli_fetch_array ($result);
        $login_is_admin = $row["family_is_admin"];
        // Set a session variable to indicate the user is logged in
        $_SESSION['family_email'] = $login_email;
        $_SESSION['family_is_admin'] = $login_is_admin;
        $_SESSION['family_id'] = $row["family_id"];


        // Redirect to the desired page
        if ($_SESSION['family_is_admin'] == "1") {
            // Redirect to the admin dashboard
            header("Location: admin_requests_list.php");
            exit();
        } else {
            // Redirect to the user dashboard
            header("Location: family_requests_list.php");
            exit();
        }
    } else {
        // Display a prompt for incorrect credentials
        echo "Invalid email or password. Please try again.";
    }
}

// Close the connection
mysqli_close($conn);

?>

<!DOCTYPE html>
<html dir="rtl" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>

    <h2>התחברות</h2>
    
    <form method="post" action="">
        <label for="email">אימייל:</label>
        <input type="email" name="email" value = "a@a" required>
        <br>
        <label for="password">סיסמא:</label>
        <input type="password" name="password" value = "a" required>
        <br>
        <input type="submit" value="התחבר">
    </form>

</body>
</html>
