<?php

require("header.php");
if ($_SESSION['family_is_admin'] == "0") header("Location: disconnect.php");

if (isset($_POST["add"]))
{
    echo "did add";
    $result = mysqli_query($conn,"insert into file_type (file_type_name) values ('$_POST[add_file_type_name]')"); 
}
//לעדכן
if (isset($_POST["update"]))
{
    echo "did update " .$_POST["update"];
}

if (isset($_POST["save"]))
{
    echo "did save " .$_POST["save"]. "   " . $_POST["file_type_name"];
    $result = mysqli_query($conn,"UPDATE file_type set file_type_name='$_POST[file_type_name]' where file_type_id = $_POST[save]");
}
if (isset($_POST["delete"]))
{
    $result = mysqli_query($conn,"UPDATE file_type set file_type_is_deleted = 1 where file_type_id = $_POST[delete]");
}
echo "<br>here is the page     settings.php <br>";
?>

<?php
echo "<form method ='post'>";
echo "<input name='add_file_type_name'>";
echo "<button name='add' value='add'>הוסף</button>";


$result = mysqli_query($conn,"SELECT file_type_id, file_type_name FROM file_type WHERE file_type_is_deleted = 0 order by file_type_name");
// בניית הטבלה בשביל התוצאה							


echo "<table border = 1 class ='settings'>";
echo "<tr><th>id</th>";
echo "<th>name</th>";
echo "<th></th></tr>";

while ($row = mysqli_fetch_array ($result))
{
    if (isset($_POST["update"]) && $_POST["update"] == $row["file_type_id"] )
    {           
        echo "<tr><td>$row[file_type_id]</td>";
        echo "<td><input name='file_type_name'  value='$row[file_type_name]'></td>";
        echo "<td><button name='save' value='$row[file_type_id]'>שמור</button> </td></tr>";
    }
    else
    {
        echo "<tr><td>$row[file_type_id]</td>";
        echo "<td>$row[file_type_name]</td>";
        echo "<td><button name='update' value='$row[file_type_id]'>לעדכן</button>";
        echo "<button name='delete' value='$row[file_type_id]' onclick=\"return confirm('האם אתה בטוח?');\">מחק</button> </td></tr>";
    }
}



echo "</table>";
echo "</form>";
require("footer.php");
?>