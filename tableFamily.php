<?php

// Establish a database connection
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "project";

// Create a connection
$conn = mysqli_connect("localhost", "root", "", "project");

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get the form data
    
    $family_firstname = $_POST['family_firstname'];
    $family_lastname = $_POST['family_lastname'];
    $family_email = $_POST['family_email'];
    $family_password = $_POST['family_password'];
    

    /// Prepare the SQL query with column names
    $sql = "INSERT INTO family (family_id, family_firstname, family_lastname, family_email, family_password) 
    VALUES (NULL, '$family_firstname', '$family_lastname', '$family_email', password('$family_password'))";


    // Execute the query
    if (mysqli_query($conn, $sql)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Close the connection
mysqli_close($conn);

?>