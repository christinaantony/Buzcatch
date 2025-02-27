<?php
header("Content-Type: application/json");
include 'db.php'; // Database connection

// Get raw JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (!isset($data['id'])) {
    echo json_encode(["error" => "MDS Member ID is required"]);
    exit;
}

$id = $data['id'];

// Check if the record exists
$stmt = $conn->prepare("SELECT id FROM mdsmembers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows == 0) {
    echo json_encode(["error" => "Record not found"]);
    exit;
}
$stmt->close();

// Delete record
$stmt = $conn->prepare("DELETE FROM mdsmembers WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["message" => "MDS Member deleted successfully"]);
} else {
    echo json_encode(["error" => "Failed to delete MDS Member", "debug" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
