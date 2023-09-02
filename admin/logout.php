<?php 

// Start Session
session_start();
// Unset Session Data
session_unset();
// Session Destroy
session_destroy();

header("Location: index.php");
exit();

?>