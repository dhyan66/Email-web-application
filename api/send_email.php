<?php
session_start();
include('../db.php');

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "fail", "message" => "Not logged in"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['recipient']) || !isset($data['subject']) || !isset($data['body'])) {
    echo json_encode(["status" => "fail", "message" => "Missing fields"]);
    exit;
}

$sender_id = $_SESSION['user_id'];
$recipient_email = $data['recipient'];
$subject = $data['subject'];
$body = $data['body'];

// Get recipient_id
$stmt = $conn->prepare("SELECT user_id FROM user_info WHERE email = ?");
$stmt->bind_param("s", $recipient_email);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows !== 1) {
    echo json_encode(["status" => "fail", "message" => "Recipient not found"]);
    exit;
}

$recipient_id = $res->fetch_assoc()['user_id'];

// Insert email
$insert = $conn->prepare("INSERT INTO emails (sender_id, recipient_id, subject, body) VALUES (?, ?, ?, ?)");
$insert->bind_param("iiss", $sender_id, $recipient_id, $subject, $body);

if ($insert->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "fail", "message" => "Database error"]);
}
