<?php
// Display errors for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include('../db.php');

header('Content-Type: application/json');

// Check authentication
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Not authenticated"]);
    exit;
}

$user_id = $_SESSION['user_id'];

// SQL query
$sql = "SELECT e.subject, e.body, e.timestamp, u.email AS sender
        FROM emails e
        JOIN user_info u ON e.sender_id = u.user_id
        WHERE e.recipient_id = ?
        ORDER BY e.timestamp DESC";

$stmt = $conn->prepare($sql);

// Catch query errors
if (!$stmt) {
    echo json_encode(["error" => "SQL prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Collect and return emails
$emails = [];
while ($row = $result->fetch_assoc()) {
    $emails[] = $row;
}

echo json_encode($emails);
