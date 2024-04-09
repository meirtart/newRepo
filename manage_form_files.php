<?php

require("header.php");
if ($_SESSION['family_is_admin'] == "0") header("Location: disconnect.php");

if (isset($_POST["add"]))
{
   // var_dump($_POST);
    
   $result = mysqli_query($conn,"insert into file_list (file_list_type_id,file_list_is_required,file_list_text, file_list_request_type_id) values ($_POST[file_type], $_POST[is_req], '$_POST[file_text]', $_POST[form_type])"); 
    echo "did add";
}
//לפתוח את אפשרות לערוך
if (isset($_POST["update"]))
{
    echo "did update " .$_POST["update"];
}
//ביצוע העריכה בפועל
if (isset($_POST["save"]))
{
    //echo "did save " .$_POST["save"]. "   " . $_POST["file_list_text"];
    $result = mysqli_query($conn,"UPDATE file_list set file_list_text='$_POST[file_list_text]', file_list_is_required=$_POST[is_req] where file_list_id = $_POST[save]");
}
if (isset($_POST["delete"]))
{
    $result = mysqli_query($conn,"UPDATE file_list set file_list_is_deleted = 1 where file_list_id = $_POST[delete]");
}
echo "<br>here is the page     settings.php <br>";
?>


<form method ='post'>
<select name="file_type" required>
    <?php
    // Modify the SQL query to fetch options from the database
    $sql = "SELECT file_type_name, file_type_id FROM file_type WHERE file_type_is_deleted = 0"; 
    $result = mysqli_query($conn, $sql);

    // Loop through the results and generate options
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . htmlspecialchars($row['file_type_id']) . "'>" . htmlspecialchars($row['file_type_name']) . "</option>";
    }
    ?>
</select>
<input type="text" name="file_text" required placeholder="הוסף תיאור לקובץ">
<a>סמן:</a>
<input type="radio" id="1" value="1" name="is_req" checked >
<label for="1">חובה</label>
<input type="radio" id="2" value="0" name="is_req" >
<label for="2">רשות</label> &nbsp &nbsp
<span>סמן:</span>
<input type="radio" id="1" value="1" name="form_type" checked >
<label for="1">טופס עזרה</label>
<input type="radio" id="2" value="0" name="form_type" >
<label for="2">טופס הלוואה</label>


 <button name='add' value='add'>הוסף</button>
 </form>

<form method="post">
<?php
$result = mysqli_query($conn,"SELECT file_list.file_list_id, file_type.file_type_name, file_list_is_required,file_list.file_list_text
FROM file_list, file_type
WHERE file_list.file_list_type_id = file_type.file_type_id
AND file_list.file_list_is_deleted = 0 
ORDER BY file_list_text");
// בניית הטבלה בשביל התוצאה							


echo "<table border = 1 class ='settings'>";
echo "<tr><th>id</th>";
echo "<th>שם קובץ</th>";
echo "<th>חובה</th>";
echo "<th>תיאור</th>";
echo "<th>עריכה</th>";
echo "</tr>";

while ($row = mysqli_fetch_array ($result))
{
    if (isset($_POST["update"]) && $_POST["update"] == $row["file_list_id"] )
    {           
        echo "<tr><td>$row[file_list_id]</td>";
        echo "<td>$row[file_type_name]</td>";

      //  if($row["file_list_is_required"]==1)
            $checked_req = "";
            $checked_not_req = "";

            if($row["file_list_is_required"]==1)
                $checked_req = "checked";
            else
                $checked_not_req = "checked";

            echo '<td>';
            echo  '<input type="radio" id="1" value="1" name="is_req" '.$checked_req.' >';
            echo  '<label for="1">חובה</label>';
            echo  '<input type="radio" id="2" value="0" name="is_req" '.$checked_not_req.' >';
            echo  '<label for="2">רשות</label> &nbsp &nbsp';
            echo '</td>';


   
        echo "<td><input name='file_list_text'  value='$row[file_list_text]'></td>";
        echo "<td><button name='save' value='$row[file_list_id]'>שמור</button> </td></tr>";
    }
    else
    {
        echo "<tr><td>$row[file_list_id]</td>";
        echo "<td>$row[file_type_name]</td>";
        if($row["file_list_is_required"]==1)
            echo "<td>חובה</td>";
        else
            echo "<td></td>";
        echo "<td>$row[file_list_text]</td>";
        echo "<td><button name='update' value='$row[file_list_id]'>לעדכן</button>";
        echo "<button name='delete' value='$row[file_list_id]' onclick=\"return confirm('האם אתה בטוח?');\">מחק</button> </td></tr>";
    }
}



echo "</table>";
echo "</form>";
require("footer.php");
?>