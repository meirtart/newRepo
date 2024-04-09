<?php
session_start();



    // Perform necessary actions, such as storing the data in the database
    $conn = mysqli_connect("localhost", "root", "", "project");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

  //  if ($is_guarantee == true)
    //     return;
    

  //var_dump($_SESSION);
// Check if the user is logged in
if (!isset($_SESSION['family_email'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}
  

?>

<!DOCTYPE html>
<html dir="rtl" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Request Form</title>
</head>
<body>



<h2>שלום,  <?php echo $_SESSION['family_email']; ?>!</h2>
    
<?php
if ($_SESSION['family_is_admin'] == "0") {
    ?>
    <div class="navbar">
    <a href="help_request_form.php">בקשה לתמיכה/סיוע</a>
    <a href="loan_request_form.php">בקשת הלוואה</a>
    <a href="file_upload.php">העלאת טפסים</a>
    <a href ="family_requests_list.php">הבקשות שלי</a>
    <a href ="disconnect.php">התנתק</a>
    </div>

<?php
}
else
{
?>
<div class="navbar">
    <a href ="admin_requests_list.php">הבקשות שלי</a>
    <a href ="disconnect.php">התנתק</a>
    <a href ="settings.php">הגדרות</a>
</div>
<?php
}
