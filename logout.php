<?php
// Logout page: clear the user session and redirect to the login screen.
require_once __DIR__ . '/includes/auth.php';

$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
// End the session completely.
session_destroy();
header('Location: login.php');
exit;
