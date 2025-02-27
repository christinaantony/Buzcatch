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

// Validate required fields
if (!$id || !$companyid) {
    echo json_encode(["error" => "ID and Company ID are required"]);
    exit;
}

// Fetch existing member and get the thumbnail image
$stmt = $conn->prepare("SELECT thumbnail_image FROM members WHERE id = ? AND companyid = ?");
$stmt->bind_param("ii", $id, $companyid);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(["error" => "Member not found"]);
    exit;
}

$stmt->bind_result($thumbnail_image);
$stmt->fetch();
$stmt->close();

// Delete the member
$stmt = $conn->prepare("DELETE FROM members WHERE id = ? AND companyid = ?");
$stmt->bind_param("ii", $id, $companyid);

if ($stmt->execute()) {
    // Delete the associated image if exists
    if ($thumbnail_image) {
        $image_path = __DIR__ . "/uploads/" . $thumbnail_image;
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    echo json_encode(["message" => "Member deleted successfully"]);
} else {
    echo json_encode(["error" => "Failed to delete member"]);
}

$stmt->close();
$conn->close();
?>
