<?php
require("header.php");

// Retrieve the session variable
$family_id = ($_SESSION['family_id']);


// SQL query to select data from two tables based on family_id
$sql = "SELECT 'בקשה לסיוע' AS table_name,  help_request_id, DATE_FORMAT(help_request_date, '%d-%m-%Y') AS formatted_date, help_request_status FROM help_request 
        WHERE help_request_family_id = $family_id 
        UNION
        SELECT 'בקשה להלוואה' AS table_name, loan_request_id, DATE_FORMAT(loan_request_date, '%d-%m-%Y') AS formatted_date, loan_request_status  FROM loan_request 
        WHERE loan_request_family_id = $family_id";
$result = mysqli_query($conn, $sql);

if ($result) {
    // Display table headers
    echo "<table border='1'>
            <tr>
                <th>מספר</th>
                <th>סוג</th>
                <th>תאריך</th>
                <th>סטטוס</th>
 
            </tr>";
    
    // Display data rows
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['help_request_id'] . "</td>";
        echo "<td>" . $row['table_name'] . "</td>";
        echo "<td>" . $row['formatted_date'] . "</td>";
        echo "<td>" . $row['help_request_status'] . "</td>";
        // Add more columns as needed
        echo "</tr>";
    }
    
    // Close the table
    echo "</table>";
} else {
    // Handle the case where the query fails
    echo "Error: " . mysqli_error($conn);
}




echo "<br>here is the page";

require("footer.php");
?>
