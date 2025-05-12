<?php
$conn = new mysqli("localhost", "root", "", "email_app"); // no password
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
