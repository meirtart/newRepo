<?php

require("header.php");
if ($_SESSION['family_is_admin'] == "0") header("Location: disconnect.php");

echo "<br>here is the page     settings.php <br>";
?>
<a href="manage_category.php">קטגוריות</a>
<a href="manage_files.php"> ניהול קבצים</a>
<a href="manage_form_files.php">שינוי טפסי חובה &nbsp </a>
<a href ="manage_users.php">עריכת משתמשים</a>
<!-- <a href ="disconnect.php">עריכת</a> -->


<?php

require("footer.php");
?>