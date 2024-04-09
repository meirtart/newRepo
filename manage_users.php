<?php

require("header.php");
if ($_SESSION['family_is_admin'] == "0") header("Location: disconnect.php");


if (isset($_POST["add"])) {
    // Check if email is provided
    if (isset($_POST["email"]) && !empty($_POST["email"])) {
        // Perform your function here
        echo "did add";
        $email = $_POST["email"];
        $result = mysqli_query($conn, "UPDATE family SET family_is_admin = '1' WHERE family.family_email = '$email'");
    }}

if (isset($_POST["delete"]))
{
    $result = mysqli_query($conn,"UPDATE family SET family_is_admin = '0' WHERE family.family_id = $_POST[delete] ");
}

?>


<form method="post" action="">
<label for="email">הוסף מייל</label><br>
<input type="email" id="email" name="email">
<input type="submit" name="add" value="Add">
</form>

<?php
$result = mysqli_query($conn,"SELECT family_id, family_firstname FROM family WHERE family_is_admin = 1 ORDER BY family_firstname");
// בניית הטבלה בשביל התוצאה							


echo "<table border = 1 class ='settings'>";
echo "<tr><th>id</th>";
echo "<th>name</th>";
echo "<th></th></tr>";





while ($row = mysqli_fetch_array($result)) {
    echo "<tr><td>{$row['family_id']}</td>";
    
    if (isset($_POST["update"]) && $_POST["update"] == $row["family_id"]) {
        echo "<form method='post'>";
        echo "<td><input type='text' name='family_name' value='{$row['family_firstname']}'></td>";
        echo "<input type='hidden' name='family_id' value='{$row['family_id']}'>";
        echo "<td><button type='submit' name='save'>שמור</button></td>";
        echo "</form>";
    } else {
        echo "<td>{$row['family_firstname']}</td>";
        echo "<td><form method='post'>";
        echo "<button type='submit' name='delete' value='{$row['family_id']}' onclick=\"return confirm('האם אתה בטוח?');\">מחק</button>";
        echo "</form></td></tr>";
    }
}





echo "</table>";
echo "</form>";
require("footer.php");
?>