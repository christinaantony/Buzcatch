<?php
header("Content-Type: application/json");
include 'db.php'; // Include database connection

// Check if request is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}

// Get form data
$id = $_POST['id'] ?? null;
$name = $_POST['name'] ?? null;
$address = $_POST['address'] ?? null;
$mobile_number = $_POST['mobile_number'] ?? null;
$email_id = $_POST['email_id'] ?? null;
$joined_date = $_POST['joined_date'] ?? null;
$account_id = $_POST['account_id'] ?? null;
$companyid = $_POST['companyid'] ?? null;

// Validate required fields
if (!$id || !$companyid) {
    echo json_encode(["error" => "ID and Company ID are required"]);
    exit;
}

// Ensure member exists
$stmt = $conn->prepare("SELECT id FROM members WHERE id = ? AND companyid = ?");
$stmt->bind_param("ii", $id, $companyid);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(["error" => "Member not found"]);
    exit;
}
$stmt->close();

// Ensure Account ID is unique within the same company
if ($account_id) {
    $stmt = $conn->prepare("SELECT id FROM members WHERE account_id = ? AND companyid = ? AND id != ?");
    $stmt->bind_param("iii", $account_id, $companyid, $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["error" => "Account ID already exists within this company"]);
        exit;
    }
    $stmt->close();
}

// Handle thumbnail image upload if provided
$thumbnail_image = null;
if (!empty($_FILES['thumbnail_image']['name'])) {
    $file_ext = pathinfo($_FILES['thumbnail_image']['name'], PATHINFO_EXTENSION);
    $thumbnail_image = md5($id) . "." . $file_ext;
    $upload_path = __DIR__ . "/uploads/" . $thumbnail_image;

    if (!move_uploaded_file($_FILES['thumbnail_image']['tmp_name'], $upload_path)) {
        echo json_encode(["error" => "Failed to upload file"]);
        exit;
    }
}

// Build update query dynamically
$update_fields = [];
$params = [];
$param_types = "";

// Add fields to update if provided
if ($name) {
    $update_fields[] = "name = ?";
    $params[] = $name;
    $param_types .= "s";
}
if ($address) {
    $update_fields[] = "address = ?";
    $params[] = $address;
    $param_types .= "s";
}
if ($mobile_number) {
    $update_fields[] = "mobile_number = ?";
    $params[] = $mobile_number;
    $param_types .= "s";
}
if ($email_id) {
    $update_fields[] = "email_id = ?";
    $params[] = $email_id;
    $param_types .= "s";
}
if ($joined_date) {
    $update_fields[] = "joined_date = ?";
    $params[] = $joined_date;
    $param_types .= "s";
}
if ($account_id) {
    $update_fields[] = "account_id = ?";
    $params[] = $account_id;
    $param_types .= "i";
}
if ($thumbnail_image) {
    $update_fields[] = "thumbnail_image = ?";
    $params[] = $thumbnail_image;
    $param_types .= "s";
}

// If no fields to update, exit
if (empty($update_fields)) {
    echo json_encode(["error" => "No fields to update"]);
    exit;
}

// Finalize query
$update_fields_str = implode(", ", $update_fields);
$sql = "UPDATE members SET $update_fields_str WHERE id = ? AND companyid = ?";
$params[] = $id;
$params[] = $companyid;
$param_types .= "ii";

// Prepare and execute
$stmt = $conn->prepare($sql);
$stmt->bind_param($param_types, ...$params);

if ($stmt->execute()) {
    echo json_encode(["message" => "Member updated successfully"]);
} else {
    echo json_encode(["error" => "Failed to update member"]);
}

$stmt->close();
$conn->close();
?>
