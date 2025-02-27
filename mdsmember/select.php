<?php
header("Content-Type: application/json");
include 'db.php'; // Database connection

// Get raw JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    // Fetch specific MDS member
    $id = $data['id'];
    $stmt = $conn->prepare("SELECT * FROM mdsmembers WHERE id = ?");
    $stmt->bind_param("i", $id);
} else {
    // Fetch all MDS members
    $stmt = $conn->prepare("SELECT * FROM mdsmembers");
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    echo json_encode(["data" => $rows]);
} else {
    echo json_encode(["error" => "No records found"]);
}

$stmt->close();
$conn->close();
?>
