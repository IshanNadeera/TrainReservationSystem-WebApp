<?php
session_start();

//destroy the session data
session_destroy();

// Redirect to a login page
header('Location: index.php');
exit;

?>