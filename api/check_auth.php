<?php
// api/check_auth.php
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
