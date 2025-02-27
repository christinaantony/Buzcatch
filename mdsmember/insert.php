<?php
header("Content-Type: application/json");
include 'db.php'; // Database connection

// Get raw JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (!isset($data['companyid'], $data['mds_id'], $data['memberid'], $data['joining_date'])) {
    echo json_encode(["error" => "All fields are required"]);
    exit;
}

// Extract data
$companyid = $data['companyid'];
$mds_id = $data['mds_id'];
$memberid = $data['memberid'];
$joining_date = $data['joining_date']; // Ensure date is captured

// Convert to correct format (YYYY-MM-DD)
$formatted_date = date('Y-m-d', strtotime($joining_date));

// Ensure company, MDS, and member exist
$stmt = $conn->prepare("SELECT id FROM company WHERE id = ?");
$stmt->bind_param("i", $companyid);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows == 0) {
    echo json_encode(["error" => "Invalid Company ID"]);
    exit;
}
$stmt->close();

$stmt = $conn->prepare("SELECT mds_id FROM mds WHERE mds_id = ?");
$stmt->bind_param("s", $mds_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows == 0) {
    echo json_encode(["error" => "Invalid MDS ID"]);
    exit;
}
$stmt->close();

$stmt = $conn->prepare("SELECT id FROM members WHERE id = ?");
$stmt->bind_param("i", $memberid);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows == 0) {
    echo json_encode(["error" => "Invalid Member ID"]);
    exit;
}
$stmt->close();

// Insert record with joining_date
$stmt = $conn->prepare("INSERT INTO mdsmembers (companyid, mds_id, memberid, joining_date) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $companyid, $mds_id, $memberid, $formatted_date);

if ($stmt->execute()) {
    echo json_encode(["message" => "MDS Member added successfully"]);
} else {
    echo json_encode(["error" => "Failed to add MDS Member", "debug" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
