<?php

require("header.php");
if ($_SESSION['family_is_admin'] == "0") header("Location: disconnect.php");

echo "<br>here is the page     admin_requests_list";

require("footer.php");
