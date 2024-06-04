<?php
require("header.php");

// Initialize variables for form data
$cell_number = '';
$explanation = '';
$category = '';
$success_message = '';

// Handle the form submission if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and process the form data here

    // Check if the keys exist in the $_POST array
    $cell_number = isset($_POST['cell_number']) ? $_POST['cell_number'] : '';
    $explanation = isset($_POST['explanation']) ? $_POST['explanation'] : '';
    $category = isset($_POST['category']) ? $_POST['category'] : '';


    // Escape user inputs to prevent SQL injection
    $escaped_cell_number = mysqli_real_escape_string($conn, $cell_number);
    $escaped_explanation = mysqli_real_escape_string($conn, $explanation);
    $escaped_category = mysqli_real_escape_string($conn, $category);


    // Get the current date and time
    $current_date = date("Y-m-d H:i:s");

    // Prepare the SQL query
    $sql = "INSERT INTO help_request 
    (help_request_family_id, help_request_phone_number, 
    help_request_date, help_request_category_id, help_request_status)
    VALUES ('{$_SESSION['family_id']}', '$escaped_cell_number', 
    '$current_date', '$escaped_category', 'pending')";

    // Execute the query
  
    if (mysqli_query($conn, $sql)) {

        $last_id = mysqli_insert_id($conn);
        $success_message = "Help Request Submitted successfully!";
    } else {
        $success_message = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }


//Fatal error: Uncaught mysqli_sql_exception: Column count doesn't match value count at row 1 in C:\xampp\htdocs\newgit\newRepo\help_request_form.php:37 Stack trace:
     #0 C:\xampp\htdocs\newgit\newRepo\help_request_form.php(37): mysqli_query(Object(mysqli), 'INSERT INTO hel...') 
     #1 {main} thrown in C:\xampp\htdocs\newgit\newRepo\help_request_form.php on line 37
    // File Upload Handling
    $target_directory = "uploads\\";  

    if (!is_dir($target_directory))
         mkdir ($target_directory,0777,true);

    if (!is_dir($target_directory."\\".$_SESSION["family_id"]))
        mkdir ($target_directory."\\".$_SESSION["family_id"],0777,true);


        foreach($_FILES as $key => $value)
        {
            
            $parts = explode("_", $key);
            $file_list_id =  $parts[2];
            $uploaded_files = array();
            $file_name = time()."___". $_FILES[$key]['name'];
            if ($file_name != "")
            {
                $file_tmp = $_FILES[$key]['tmp_name'];
                $target_file = $target_directory."\\".$_SESSION["family_id"] ."\\". $file_name;

                if (move_uploaded_file($file_tmp, $target_file)) {
                    $uploaded_files[] = $target_file;
                }
                
                $sql = "insert into file (file_family_id,file_type_id,file_text,file_name,file_help_request_id) 
                values ($_SESSION[family_id],(select file_list_type_id from file_list where file_list_id = $file_list_id ),(select file_list_text from file_list where file_list_id = $file_list_id ),'$file_name',$last_id)";
                mysqli_query($conn, $sql);
            }
        }









    
    
}



$filesToUpload = "";
$result = mysqli_query($conn,"select file_list_id,file_list_type_id,file_list_text, if(file_list_is_required=1,\"required\",\"\")  as file_list_is_required
                            from file_list
                            where file_list_is_deleted = 0
                            and file_list_request_type_id = 1");
while ($row = mysqli_fetch_array ($result))
{

    $filesToUpload .= "<label for='user_files'>$row[file_list_text]</label>
    <input type='file' name='user_files_$row[file_list_id]' $row[file_list_is_required]>
    <br>";
}

?>


    <h2>Help Request Form</h2>

    <?php
    // Display success message if set
    if (!empty($success_message)) {
        echo "<p>$success_message</p>";
    }


    
    ?>

    <form enctype='multipart/form-data' action="" method="post" name ="frm">
        <label for="cell_number">מספר טלפון:</label>
        <input type="text" name="cell_number" value="<?php echo htmlspecialchars($cell_number); ?>" required>
        <br>

        

        <label for="category">קטגוריה:</label>
<select name="category" required>
    <?php
    // Modify the SQL query to fetch options from the database
    $sql = "SELECT category_name, category_id FROM category WHERE category_is_deleted = 0"; 
    $result = mysqli_query($conn, $sql);

    // Loop through the results and generate options
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . htmlspecialchars($row['category_id']) . "'>" . htmlspecialchars($row['category_name']) . "</option>";
    }
    ?>
</select>
        <br>
    
       <?php echo $filesToUpload; ?>

        <input type="submit" value="Submit Help Request">
    </form>

    
<?php
require("footer.php");
