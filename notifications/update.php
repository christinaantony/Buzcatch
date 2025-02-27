<?php
header("Content-Type: application/json");
include 'db.php'; // Database connection

$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (!isset($data['id'], $data['companyid'], $data['notification_description'], $data['date'], $data['userid'], $data['type'])) {
    echo json_encode(["error" => "All fields are required"]);
    exit;
}

// Extract data
$id = $data['id'];
$companyid = $data['companyid'];
$notification_description = $data['notification_description'];
$date = $data['date'];
$userid = $data['userid'];
$type = $data['type'];

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

// Update the record
$stmt = $conn->prepare("UPDATE notification SET companyid = ?, notification_description = ?, date = ?, userid = ?, type = ? WHERE id = ?");
$stmt->bind_param("issiii", $companyid, $notification_description, $date, $userid, $type, $id);

if ($stmt->execute()) {
    echo json_encode(["message" => "Notification updated successfully"]);
} else {
    echo json_encode(["error" => "Failed to update notification"]);
}

$stmt->close();
$conn->close();
?>
