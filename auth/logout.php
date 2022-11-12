<?php
session_start();
// remove all session variables
session_unset();

// destroy the session
session_destroy();
if (!$_SESSION['admin']) {
    header('Location: /login');
} else {
    header('Location: /');
}
