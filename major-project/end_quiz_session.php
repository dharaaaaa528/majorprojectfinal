<?php
session_start();
unset($_SESSION['quiz_start_time']);
unset($_SESSION['quiz_submitted']);
header("Location: contentpage.php");
exit();
?>
