<?php
require("header.php");
//var_dump($_POST);
// Initialize variables for form data
$cell_number = '';


$signature = '';
$success_message = '';
$amount = '';
$guarantee_name = '';
$guarantee_number = '';
$guarantee_email = '';


// Handle the form submission if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and process the form data here

    // Check if the keys exist in the $_POST array
    $cell_number = isset($_POST['cell_number']) ? $_POST['cell_number'] : '';
   
   
    $amount = isset($_POST['amount']) ? $_POST['amount'] : '';
    $guarantee_name = isset($_POST['guarantee_name']) ? $_POST['guarantee_name'] : '';
    $guarantee_number = isset($_POST['guarantee_number']) ? $_POST['guarantee_number'] : '';
    $guarantee_email = isset($_POST['guarantee_email']) ? $_POST['guarantee_email'] : '';


    $signature = isset($_POST['signature']) ? $_POST['signature'] : '';

    $escaped_cell_number = mysqli_real_escape_string($conn, $cell_number);




    // Get the current date and time
    $current_date = date("Y-m-d H:i:s");

    // Prepare the SQL query
    $sql = "INSERT INTO loan_request 
    (loan_request_family_id,loan_request_status, loan_request_amount, loan_request_phone,loan_request_guarantee_name,
     loan_request_guarantee_number, loan_request_guarantee_email,  loan_request_signature,
     loan_request_date)
    VALUES ('{$_SESSION['family_id']}', 'pending','$amount', '$escaped_cell_number','$guarantee_name',
     '$guarantee_number', '$guarantee_email',  '$signature',
    '$current_date')";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        $last_id = mysqli_insert_id($conn);
        $success_message = "loan Request Submitted successfully! ";
        echo $_SERVER['HTTP_HOST']."/Theproject/guarantee_signature.php?id=".$last_id;
    } else {
        $success_message = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }



    // File Upload Handling
    $target_directory = "uploads\\";  // Change this directory to your desired location

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
                
                $sql = "insert into file (file_family_id,file_type_id,file_text,file_name, file_loan_request_id) 
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


    <h2>loan Request Form</h2>

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
        <label for="amount">סכום מבוקש:</label>
        <input type="text" name="amount" value="<?php echo htmlspecialchars($amount); ?>" required>
        <br>
        <label for="guarantee_name">שם ערב</label>
        <input type="text" name="guarantee_name" value="<?php echo htmlspecialchars($guarantee_name); ?>" required>
        <br>
        <label for="guarantee_number">טלפון ערב</label>
        <input type="text" name="guarantee_number" value="<?php echo htmlspecialchars($guarantee_number); ?>" required>
        <br>
        <label for="guarantee_email">אימייל ערב:</label>
        <input type="email" name="guarantee_email" value="<?php echo htmlspecialchars($guarantee_email); ?>" required>
        <br>
        

        
    
       <?php echo $filesToUpload; ?>

       
       <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var form = document.querySelector('form');
        var signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
            backgroundColor: 'rgb(255, 255, 255)' // set background color
        });

        function clearSignature() {
            signaturePad.clear();
        }

        form.addEventListener('submit', function(e) {
            console.log("Form submitted!");
            // Prevent the default form submission
            e.preventDefault();

            // Get the signature data as a base64-encoded string
            var signatureData = signaturePad.toDataURL();

            // Update the value of the hidden input field with the signature data
            document.getElementById('signature').value = signatureData;

            // Submit the form programmatically
            this.submit();
        });
    });
</script>




       <div style="border: 1px solid #ccc; padding: 1px; width: 400px;">
        <canvas name="signature" id="signature-pad" class="signature-pad" style="width: 100%; margin: auto;" width="400" height="200"></canvas>
    </div>
       <script>
        var canvas = document.getElementById('signature-pad');
        var signaturePad = new SignaturePad(canvas, {
    backgroundColor: 'rgb(255, 255, 255)' // set background color
  });
        </script>
        <input type="hidden" name="signature" id="signature" required> 
    <button type="button" onclick="signaturePad.clear()">Clear Signature</button>



        <input type="submit" value="Submit loan Request">
    </form>

    
<?php
require("footer.php");
