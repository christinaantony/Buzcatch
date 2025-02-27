<?php
header("Content-Type: application/json");
include 'db.php'; // Include database connection

// Ensure request method is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (!isset($data['mds_id']) || !isset($data['companyid'])) {
    echo json_encode(["error" => "MDS ID and Company ID are required", "received_data" => $data]);
    exit;
}

$mds_id = $data['mds_id'];
$companyid = $data['companyid'];

// Check if the record exists
$stmt = $conn->prepare("SELECT 1 FROM MDS WHERE mds_id = ? AND companyid = ?");
$stmt->bind_param("ii", $mds_id, $companyid);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(["error" => "MDS record not found"]);
    exit;
}
$stmt->close();

// Delete record
$stmt = $conn->prepare("DELETE FROM MDS WHERE mds_id = ? AND companyid = ?");
$stmt->bind_param("ii", $mds_id, $companyid);

if ($stmt->execute()) {
    echo json_encode(["message" => "MDS record deleted successfully"]);
} else {
    echo json_encode(["error" => "Failed to delete MDS record"]);
}

$stmt->close();
$conn->close();
?>
