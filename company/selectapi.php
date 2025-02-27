<?php
header("Content-Type: application/json");
include 'db.php';

if (!isset($_GET['id'])) {
    echo json_encode(["error" => "ID is required"]);
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT id, company_name, company_address, email_id, mobile_number FROM company WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode($data ?: ["error" => "No record found"]);
?>
