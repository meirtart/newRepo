<?php

require("header.php");
if ($_SESSION['family_is_admin'] == "0") header("Location: disconnect.php");

if (isset($_POST["add"]))
{
    echo "did add";
    $result = mysqli_query($conn,"insert into category (category_name) values ('$_POST[add_category_name]')"); 
}
//לעדכן
if (isset($_POST["update"]))
{
    echo "did update " .$_POST["update"];
}

if (isset($_POST["save"]))
{
    echo "did save " .$_POST["save"]. "   " . $_POST["category_name"];
    $result = mysqli_query($conn,"UPDATE category set category_name='$_POST[category_name]' where category_id = $_POST[save]");
}
if (isset($_POST["delete"]))
{
    $result = mysqli_query($conn,"UPDATE category set category_is_deleted = 1 where category_id = $_POST[delete]");
}
echo "<br>here is the page     settings.php <br>";
?>

<?php
echo "<form method ='post'>";
echo "<input name='add_category_name'>";
echo "<button name='add' value='add'>הוסף</button>";


$result = mysqli_query($conn,"SELECT category_id,category_name FROM category WHERE category_is_deleted = 0 order by category_name");
// בניית הטבלה בשביל התוצאה							


echo "<table border = 1 class ='settings'>";
echo "<tr><th>id</th>";
echo "<th>name</th>";
echo "<th></th></tr>";

while ($row = mysqli_fetch_array ($result))
{
    if (isset($_POST["update"]) && $_POST["update"] == $row["category_id"] )
    {
        echo "<tr><td>$row[category_id]</td>";
        echo "<td><input name='category_name'  value='$row[category_name]'></td>";
        echo "<td><button name='save' value='$row[category_id]'>שמור</button> </td></tr>";
    }
    else
    {
        echo "<tr><td>$row[category_id]</td>";
        echo "<td>$row[category_name]</td>";
        echo "<td><button name='update' value='$row[category_id]'>לעדכן</button>";
        echo "<button name='delete' value='$row[category_id]' onclick=\"return confirm('האם אתה בטוח?');\">מחק</button> </td></tr>";
    }
}



echo "</table>";
echo "</form>";
require("footer.php");
?>