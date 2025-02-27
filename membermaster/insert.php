<?php
header("Content-Type: application/json");
include 'db.php'; // Ensure correct database connection

// Get form data
$name = $_POST['name'] ?? null;
$address = $_POST['address'] ?? null;
$mobile_number = $_POST['mobile_number'] ?? null;
$email_id = $_POST['email_id'] ?? null;
$joined_date = $_POST['joined_date'] ?? null;
$account_id = $_POST['account_id'] ?? null;  // Take input directly
$companyid = $_POST['companyid'] ?? null;

// Validate required fields
if (!$name || !$companyid || !$account_id) {
    echo json_encode(["error" => "Name, Company ID, and Account ID are required"]);
    exit;
}

// 🔹 Validate `companyid` exists in `employee` table
$stmt = $conn->prepare("SELECT 1 FROM employee WHERE companyid = ?");
$stmt->bind_param("i", $companyid);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    echo json_encode(["error" => "Invalid Company ID. No matching record found in employee table."]);
    exit;
}
$stmt->close();

// 🔹 Check if `account_id` is already used in this `companyid`
$stmt = $conn->prepare("SELECT 1 FROM members WHERE account_id = ? AND companyid = ?");
$stmt->bind_param("ii", $account_id, $companyid);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(["error" => "Account ID already exists within this company"]);
    exit;
}
$stmt->close();

// 🔹 Generate the next `id` for the members table
$query = "SELECT COALESCE(MAX(id), 0) + 1 AS new_id FROM members";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$new_id = $row['new_id'] ?? 1;
$stmt->close();

// 🔹 Handle Thumbnail Upload (if provided)
$thumbnail_image = null;
if (!empty($_FILES['thumbnail_image']['name'])) {
    $upload_dir = __DIR__ . "/uploads/"; // Ensure this folder exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Generate unique encrypted filename using `id`
    $file_ext = pathinfo($_FILES['thumbnail_image']['name'], PATHINFO_EXTENSION);
    $thumbnail_image = md5($new_id) . "_thumbnail." . $file_ext;
    $target_path = $upload_dir . $thumbnail_image;

    if (!move_uploaded_file($_FILES['thumbnail_image']['tmp_name'], $target_path)) {
        echo json_encode(["error" => "Failed to upload file"]);
        exit;
    }
}

// 🔹 Insert Data into `members` Table
$sql = "INSERT INTO members (id, name, address, mobile_number, email_id, joined_date, created_at, account_id, companyid, thumbnail_image) 
        VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["error" => "SQL Prepare Failed: " . $conn->error]); // Debugging Output
    exit;
}

$stmt->bind_param("isssssiss", $new_id, $name, $address, $mobile_number, $email_id, $joined_date, $account_id, $companyid, $thumbnail_image);

if ($stmt->execute()) {
    echo json_encode(["message" => "Member added successfully", "id" => $new_id, "account_id" => $account_id, "companyid" => $companyid]);
} else {
    echo json_encode(["error" => "Failed to insert member", "sql_error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
