<?php
session_start();

// Checks if a user exists in the session so that people cannot visit this page without logging in.
if (!isset($_SESSION['user'])) {
// If a user is not logged in, they are redirected to the login page.
    header('Location: login.php');
}
// Checks if it is the users first login so that they are redirected to the password reset page.
if ($_SESSION['firstTimeLogin'] == 'true') {
    header('Location: setpassword.php');
}


