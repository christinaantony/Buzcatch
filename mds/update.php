<?php
header("Content-Type: application/json");
include 'db.php'; // Include database connection

// Get JSON input
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Debugging - Print received data
if (!$data) {
    echo json_encode(["error" => "Invalid JSON input", "debug" => $input]);
    exit;
}

// Get form data from JSON
$mds_id = $data['mds_id'] ?? null;
$companyid = $data['companyid'] ?? null;
$mds_name = $data['mds_name'] ?? null;  // Fix field name
$total_salary = $data['total_salary'] ?? null;
$starting_date = $data['starting_date'] ?? null;
$number_of_installments = $data['number_of_installments'] ?? null;
$end_date = $data['end_date'] ?? null;

// Validate required fields
if (!$mds_id || !$companyid || !$mds_name || !$total_salary || !$starting_date || !$number_of_installments || !$end_date) {
    echo json_encode([
        "error" => "All fields are required",
        "received_data" => $data  // Debugging: Show what was received
    ]);
    exit;
}

// Check if the record exists
$stmt = $conn->prepare("SELECT 1 FROM mds WHERE mds_id = ? AND companyid = ?");
$stmt->bind_param("ii", $mds_id, $companyid);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(["error" => "MDS record not found"]);
    exit;
}
$stmt->close();

// Update the record
$stmt = $conn->prepare("UPDATE mds SET mds_name=?, total_salary=?, starting_date=?, number_of_installments=?, end_date=? WHERE mds_id=? AND companyid=?");
$stmt->bind_param("sisssii", $mds_name, $total_salary, $starting_date, $number_of_installments, $end_date, $mds_id, $companyid);

if ($stmt->execute()) {
    echo json_encode(["message" => "MDS record updated successfully"]);
} else {
    echo json_encode(["error" => "Failed to update MDS record"]);
}

$stmt->close();
$conn->close();
?>
