<?php

require("header.php");
if ($_SESSION['family_is_admin'] == "0") header("Location: disconnect.php");

        
$number = "";
$type = "";
$date = "";
$last_name = "";
$phone_number = "";
$status = "";

if (isset($_POST["number"]))
{
    $number = $_POST["number"];
    $type = $_POST["type"];
    $date = $_POST["date"];
    $last_name = $_POST["last_name"];
    $phone_number = $_POST["phone_number"];
    $status = $_POST["status"];
}

$where = "";
if ($number != "") $where .= " and help_request_id like '%".$number."%'";
if ($type != "") $where .= " and table_name like '%".$type."%'";
if ($date != "") $where .= " and formatted_date like '%".$date."%'";
if ($last_name != "") $where .= " and family_name like '%".$last_name."%'";
if ($phone_number != "") $where .= " and help_request_phone_number like '%".$phone_number."%'";
if ($status != "") $where .= " and help_request_status like '%".$status."%'";

// Retrieve the session variable
$family_id = ($_SESSION['family_id']);

$filter = "";



// SQL query to select data from two tables based on family_id
$sql = "select 
table_name,help_request_id,formatted_date,help_request_status,help_request_family_id,concat(family.family_firstname,' ',family.family_lastname)  family_name, help_request_phone_number,help_request_category_id
from (
SELECT 'בקשה לסיוע' AS table_name,  help_request_id, DATE_FORMAT(help_request_date, '%d-%m-%Y') AS formatted_date, help_request_status, help_request_family_id, help_request_phone_number, help_request_category_id FROM help_request 
        UNION
        SELECT 'בקשה להלוואה' AS table_name, loan_request_id, DATE_FORMAT(loan_request_date, '%d-%m-%Y') AS formatted_date, loan_request_status, loan_request_family_id, loan_request_phone,''  FROM loan_request) tbl, 
        family
        where family.family_id = tbl.help_request_family_id
    and ( tbl.help_request_status like '%$filter%'
    
    or tbl.formatted_date like '%$filter%'
        or tbl.help_request_family_id like '%$filter%'
        or tbl.help_request_phone_number like '%$filter%'
        or tbl.help_request_category_id like '%$filter%')
        $where";

$result = mysqli_query($conn, $sql);

if ($result) {
    ?>
    <form method = "post">
    <input type="submit" hidden />
     <table border='1'>
            
         <tr>
         <th><input type='text' name="number" size="1" value="<?php echo $number; ?>"></th>
         <th><input type='text' name="type" size="10" value="<?php echo $type; ?>"></th>
         <th><input type='text' name="date" size="10" value="<?php echo $date; ?>"></th>
         <th><input type='text' name="last_name"size="20" value="<?php echo $last_name; ?>"></th>
         <th><input type='text' name="phone_number"size="10" value="<?php echo $phone_number; ?>"></th>
        <th><input type='text' name="status"size="12" value="<?php echo $status; ?>"></th>
        </tr>    
            <tr>
                <th>מספר</th>
                <th>סוג</th>
                <th>תאריך</th>
                <th>שם</th>
                <th>טלפון</th>
                <th>סטטום</th>
            </tr>
    <?php
    // Display data rows
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['help_request_id'] . "</td>";
        echo "<td>" . $row['table_name'] . "</td>";
        echo "<td>" . $row['formatted_date'] . "</td>";
        echo "<td>" . $row['family_name'] . "</td>";
        echo "<td>" . $row['help_request_phone_number'] . "</td>";
        echo "<td>" . $row['help_request_status'] . "</td>";
        
        echo "</tr>";
    }
    
    // Close the table
    echo "</table>";
    ?>
    </form>
    <?php
} else {
    // Handle the case where the query fails
    echo "Error: " . mysqli_error($conn);
}




echo "<br>here is the page";




require("footer.php");
