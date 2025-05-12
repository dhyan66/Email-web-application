<?php
session_start();
include('../db.php');

header('Content-Type: application/json');

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['username']) || !isset($data['password'])) {
    echo json_encode(["status" => "fail", "message" => "Missing credentials"]);
    exit;
}

$username = $data['username'];
$password = $data['password'];

$stmt = $conn->prepare("SELECT user_id, hashed_password FROM login WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['hashed_password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $lastLogin = date("Y-m-d H:i:s");

        $update = $conn->prepare("UPDATE login SET last_login_time = ? WHERE user_id = ?");
        $update->bind_param("si", $lastLogin, $user['user_id']);
        $update->execute();

        setcookie("last_login", $lastLogin, time() + 86400 * 30, "/");

        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "fail", "message" => "Incorrect password"]);
    }
} else {
    echo json_encode(["status" => "fail", "message" => "User not found"]);
}
