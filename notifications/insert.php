<?php
header("Content-Type: application/json");
include 'db.php'; // Database connection

$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (!isset($data['companyid'], $data['notification_description'], $data['date'], $data['userid'], $data['type'])) {
    echo json_encode(["error" => "All fields are required"]);
    exit;
}

// Generate new ID
$result = $conn->query("SELECT MAX(id) AS max_id FROM notification");
$row = $result->fetch_assoc();
$new_id = $row['max_id'] + 1;

// Extract data
$companyid = $data['companyid'];
$notification_description = $data['notification_description'];
$date = $data['date'];
$userid = $data['userid'];
$type = $data['type'];

// Insert record
$stmt = $conn->prepare("INSERT INTO notification (id, companyid, notification_description, date, userid, type) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iissii", $new_id, $companyid, $notification_description, $date, $userid, $type);

if ($stmt->execute()) {
    echo json_encode(["message" => "Notification added successfully", "id" => $new_id]);
} else {
    echo json_encode(["error" => "Failed to insert notification"]);
}

$stmt->close();
$conn->close();
?>
