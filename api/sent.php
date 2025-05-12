<?php
session_start();
include('../db.php');

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Not authenticated"]);
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT e.subject, e.body, e.timestamp, u.email AS recipient
        FROM emails e
        JOIN user_info u ON e.recipient_id = u.user_id
        WHERE e.sender_id = ?
        ORDER BY e.timestamp DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["error" => "SQL error: " . $conn->error]);
    exit;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$emails = [];
while ($row = $result->fetch_assoc()) {
    $emails[] = $row;
}
echo json_encode($emails);
