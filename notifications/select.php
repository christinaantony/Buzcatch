<?php
header("Content-Type: application/json");
include 'db.php'; // Database connection

$data = json_decode(file_get_contents("php://input"), true);

// Validate required field
if (!isset($data['id'])) {
    echo json_encode(["error" => "ID is required"]);
    exit;
}

$id = $data['id'];

// Fetch specific fields
$stmt = $conn->prepare("SELECT companyid, notification_description, date, userid, type FROM notification WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(["error" => "No record found"]);
}

$stmt->close();
$conn->close();
?>
