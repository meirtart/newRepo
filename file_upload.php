<!DOCTYPE html>
<html dir="rtl" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<?php
require("header.php");
var_dump($_POST);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
    if (isset($_POST["done"]))
    {
        //array(3) { ["request"]=> string(7) "help_16" ["fileType"]=> string(1) "1" ["do_upload"]=> string(22) " להעלות קובץ" }
        $parts = explode("_", $_POST["request"]);
        if ($parts[0] == "help")
        {
                $result = mysqli_query($conn,"UPDATE help_request set help_request_status='pending' where help_request_id=$parts[1]");
        }
        else
        {
            $result = mysqli_query($conn,"UPDATE loan_request set loan_request_status='pending' where loan_request_id=$parts[1]");

        }
        
    }
    else
    {
     // File Upload Handling
     $target_directory = "uploads\\";  // Change this directory to your desired location

     if (!is_dir($target_directory))
          mkdir ($target_directory,0777,true);
 
     if (!is_dir($target_directory."\\".$_SESSION["family_id"]))
         mkdir ($target_directory."\\".$_SESSION["family_id"],0777,true);
var_dump($_FILES);


             $file_name = time()."___". $_FILES['user_files']['name'];
             if ($file_name != "")
             {
                 $file_tmp = $_FILES['user_files']['tmp_name'];
                 $target_file = $target_directory."\\".$_SESSION["family_id"] ."\\". $file_name;

                 move_uploaded_file($file_tmp, $target_file);
 
                 $sql = "insert into file (file_family_id,file_type_id,file_text,file_name) 
                 values ($_SESSION[family_id], $_POST[fileType], 'קובץ נוסף שביקשו','$file_name')";
                 mysqli_query($conn, $sql);

             }
         }
        }

  ?>
<body>
<form enctype='multipart/form-data' action="" method="post" name ="frm">
<label for="request">בקשה:</label>
<select name="request" required>
    <?php
    // Modify the SQL query to fetch options from the database
    $sql = "select concat('help_',help_request.help_request_id) as id, concat('בקשה לעזרה  ',help_request.help_request_date) as text from help_request where help_request.help_request_family_id = $_SESSION[family_id] and help_request.help_request_status = 'ממתין לקבצים' 
    union
    select concat('loan_',loan_request.loan_request_id) as id, concat('בקשה להלוואה ',loan_request.loan_request_date) as text from loan_request where loan_request.loan_request_family_id = $_SESSION[family_id] and loan_request.loan_request_status = 'ממתין לקבצים' "; 
    $result = mysqli_query($conn, $sql);

    // Loop through the results and generate options
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars(str_replace(".000000","",$row['text'])) . "</option>";
    }
    
    ?>
        </select>

    <br>
    <label for="category">קטגוריה:</label>
    <select name="fileType" required>
    <?php
    
    // Modify the SQL query to fetch options from the database
    $sql = "SELECT file_list_text, file_list_id FROM file_list WHERE file_list_is_deleted = 0"; 
    $result = mysqli_query($conn, $sql);

    // Loop through the results and generate options
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . htmlspecialchars($row['file_list_id']) . "'>" . htmlspecialchars($row['file_list_text']) . "</option>";
    }
    ?>
    </select>

    <br>        
    <label for='user_files'>קובץ להעלות</label>
    <input type='file' name='user_files' >
    <br>
    <input type="submit" name="do_upload" value=" להעלות קובץ"></input>
    <input type="submit" name="done" value="סיימתי להעלות קבצים"></input>
    <br>

</form>

  







</body>
</html>