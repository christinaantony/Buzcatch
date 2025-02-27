<?php
require 'db.php'; // Database connection

header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] == "POST" || $_SERVER["REQUEST_METHOD"] == "GET") {
    // Check if 'id' is provided
    $id = isset($_POST['id']) ? trim($_POST['id']) : (isset($_GET['id']) ? trim($_GET['id']) : null);

    if (!$id) {
        echo json_encode(["error" => "Employee ID is required"]);
        exit;
    }

    // Fetch specific fields (Avoid using SELECT *)
    $sql = "SELECT id, companyid, employee_id, employee_name, employee_address, place, mail_id, pincode, mobile_number, thumbnail_image 
            FROM employee 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo json_encode($result->fetch_assoc());
        } else {
            echo json_encode(["error" => "No employee found"]);
        }
    } else {
        echo json_encode(["error" => "Failed to fetch employee"]);
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
