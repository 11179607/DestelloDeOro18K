<?php
// api/check_auth.php
$lifetime = 60 * 60 * 24 * 30; // 30 días
ini_set('session.gc_maxlifetime', $lifetime);
session_set_cookie_params($lifetime);
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    echo json_encode([
        'authenticated' => true,
        'user' => [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'role' => $_SESSION['role'],
            'name' => $_SESSION['name']
        ]
    ]);
} else {
    echo json_encode([
        'authenticated' => false
    ]);
}
?>
