<?php
header("Content-Type: application/json");
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['id'], $data['company_name'], $data['company_address'], $data['place'], $data['email_id'], $data['mobile_number'], $data['ip_address'], $data['thumbnail_image'])) {
    echo json_encode(["error" => "Missing fields"]);
    exit;
}

$stmt = $conn->prepare("UPDATE company SET company_name=?, company_address=?, place=?, email_id=?, mobile_number=?, ip_address=?, thumbnail_image=?, modified_at=CURRENT_TIMESTAMP WHERE id=?");
$stmt->bind_param("sssssssi", $data['company_name'], $data['company_address'], $data['place'], $data['email_id'], $data['mobile_number'], $data['ip_address'], $data['thumbnail_image'], $data['id']);

if ($stmt->execute()) {
    echo json_encode(["message" => "Company updated successfully"]);
} else {
    echo json_encode(["error" => $stmt->error]);
}
?>
