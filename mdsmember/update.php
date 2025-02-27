<?php
header("Content-Type: application/json");
include 'db.php'; // Database connection

$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (!isset($data['id'], $data['companyid'], $data['mds_id'], $data['memberid'], $data['joining_date'])) {
    echo json_encode(["error" => "All fields are required"]);
    exit;
}

// Extract data
$id = $data['id'];
$companyid = $data['companyid'];
$mds_id = $data['mds_id'];
$memberid = $data['memberid'];
$joining_date = $data['joining_date']; // Ensure value is captured

// Debugging: Log incoming data
error_log("Received Joining Date (Raw): " . $joining_date);

// Convert joining_date to correct format (YYYY-MM-DD)
$formatted_date = date('Y-m-d', strtotime($joining_date));
error_log("Formatted Joining Date: " . $formatted_date);

// Check if record exists
$stmt = $conn->prepare("SELECT id FROM mdsmembers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows == 0) {
    echo json_encode(["error" => "Record not found"]);
    exit;
}
$stmt->close();

// Update the record
$stmt = $conn->prepare("UPDATE mdsmembers SET companyid = ?, mds_id = ?, memberid = ?, joining_date = ? WHERE id = ?");
$stmt->bind_param("iisii", $companyid, $mds_id, $memberid, $formatted_date, $id);

if ($stmt->execute()) {
    echo json_encode(["message" => "MDS Member updated successfully"]);
} else {
    echo json_encode(["error" => "Failed to update MDS Member", "debug" => $stmt->error]);
}

// Debugging logs
error_log("SQL Error: " . $stmt->error);

$stmt->close();
$conn->close();
?>
