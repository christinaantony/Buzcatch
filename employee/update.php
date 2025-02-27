<?php
require 'db.php'; // Ensure correct DB connection

header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if required fields exist
    if (!isset($_POST['id'], $_POST['employee_name'], $_POST['place'])) {
        echo json_encode(["error" => "All fields are required"]);
        exit;
    }

    // Trim and sanitize input
    $id = trim($_POST['id']);
    $employee_name = trim($_POST['employee_name']);
    $place = trim($_POST['place']);

    // Debugging: Check exact data received
    error_log("Received ID: " . $id);
    error_log("Received Name: " . $employee_name);
    error_log("Received Place: " . $place);

    if (empty($id) || empty($employee_name) || empty($place)) {
        echo json_encode(["error" => "Invalid input, no empty values allowed"]);
        exit;
    }

    // Prepare and execute update statement
    $sql = "UPDATE employee SET employee_name = ?, place = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $employee_name, $place, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Employee updated successfully"]);
    } else {
        echo json_encode(["error" => "Failed to update employee"]);
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
