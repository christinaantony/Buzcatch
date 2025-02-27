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

// Check if record exists
$stmt = $conn->prepare("SELECT id FROM notification WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows == 0) {
    echo json_encode(["error" => "Record not found"]);
    exit;
}
$stmt->close();

// Delete record
$stmt = $conn->prepare("DELETE FROM notification WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["message" => "Notification deleted successfully"]);
} else {
    echo json_encode(["error" => "Failed to delete notification"]);
}

$stmt->close();
$conn->close();
?>
