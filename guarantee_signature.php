<?php
$is_guarantee = true;
//check if the signature is alredy there
require("header.php");
$guarantee_signature='';
$id = $_GET["id"];


$sql = "SELECT family_firstname, family_lastname, loan_request.loan_request_amount, 
            loan_request.loan_request_guarantee_name FROM family, loan_request
        WHERE loan_request_family_id = family.family_id 
        AND loan_request.loan_request_id = $id";
$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_assoc($result);
var_dump($row);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <title>Document</title>
    <form enctype='multipart/form-data' action="" method="post" name ="frm">
    
    <h2>שלום <?php echo $row['loan_request_guarantee_name']; ?></h2>
    <h3> <?php echo $row['family_firstname'].$row['family_lastname']; ?>מבקש ממך לחתום כערב להלוואה בסכום של <?php echo $row['loan_request_amount']; ?></h3>
    
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
    <button type="button" onclick="signaturePad.clear()">נקה</button>


        <input type="submit" value="שלח חתימה">
    


    
    
    
    
    
    
    
    </form>



    
</body>
</html>
