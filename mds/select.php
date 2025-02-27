<?php
header("Content-Type: application/json");
include 'db.php'; // Include database connection

// Check for POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Invalid request method. Use POST"]);
    exit;
}

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (empty($data['mds_id']) || empty($data['companyid'])) {
    echo json_encode(["error" => "mds_id and companyid are required"]);
    exit;
}

$mds_id = $data['mds_id'];
$companyid = $data['companyid'];

// Fetch specific details
$stmt = $conn->prepare("SELECT mds_id, companyid, mds_name, total_salary, starting_date, number_of_installments, end_date FROM MDS WHERE mds_id = ? AND companyid = ?");
$stmt->bind_param("ii", $mds_id, $companyid);
$stmt->execute();
$result = $stmt->get_result();

// Check if data exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(["error" => "No record found for the given mds_id and companyid"]);
}

$stmt->close();
$conn->close();
?>
