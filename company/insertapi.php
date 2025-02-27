<?php
header("Content-Type: application/json");
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['company_name'], $data['company_address'], $data['place'], $data['email_id'], $data['mobile_number'], $data['userid'], $data['ip_address'], $data['thumbnail_image'])) {
    echo json_encode(["error" => "Missing fields"]);
    exit;
}

// Generate ID as MAX(id) + 1
$result = $conn->query("SELECT COALESCE(MAX(id), 0) + 1 AS new_id FROM company");
$row = $result->fetch_assoc();
$new_id = $row['new_id'];

$stmt = $conn->prepare("INSERT INTO company (id, company_name, company_address, place, email_id, mobile_number, userid, ip_address, thumbnail_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssssss", $new_id, $data['company_name'], $data['company_address'], $data['place'], $data['email_id'], $data['mobile_number'], $data['userid'], $data['ip_address'], $data['thumbnail_image']);

if ($stmt->execute()) {
    echo json_encode(["message" => "Company added successfully", "id" => $new_id]);
} else {
    echo json_encode(["error" => $stmt->error]);
}
?>
