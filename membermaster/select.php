<?php
header("Content-Type: application/json");
include 'db.php'; // Database connection

// Check if request is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}

// Get form data
$id = $_POST['id'] ?? null;
$companyid = $_POST['companyid'] ?? null;

// Build SQL query dynamically
$sql = "SELECT id, name, address, mobile_number, email_id, joined_date, account_id, companyid, thumbnail_image FROM members WHERE 1";
$params = [];
$param_types = "";

if ($id) {
    $sql .= " AND id = ?";
    $params[] = $id;
    $param_types .= "i";
}
if ($companyid) {
    $sql .= " AND companyid = ?";
    $params[] = $companyid;
    $param_types .= "i";
}

$stmt = $conn->prepare($sql);

// Bind parameters dynamically
if (!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$members = [];
while ($row = $result->fetch_assoc()) {
    // Include full image path
    $row['thumbnail_image'] = $row['thumbnail_image'] ? "http://localhost/student_dashboard/membermaster/uploads/" . $row['thumbnail_image'] : null;
    $members[] = $row;
}

// Return results
if (count($members) > 0) {
    echo json_encode(["members" => $members]);
} else {
    echo json_encode(["error" => "No members found"]);
}

$stmt->close();
$conn->close();
?>
