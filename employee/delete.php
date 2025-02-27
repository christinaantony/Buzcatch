<?php
require 'db.php'; // Database connection

header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if 'id' is provided
    if (!isset($_POST['id'])) {
        echo json_encode(["error" => "Employee ID is required"]);
        exit;
    }

    // Sanitize and validate input
    $id = trim($_POST['id']);
    if (empty($id)) {
        echo json_encode(["error" => "Invalid Employee ID"]);
        exit;
    }

    // Prepare delete query
    $sql = "DELETE FROM employee WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(["success" => "Employee deleted successfully"]);
        } else {
            echo json_encode(["error" => "No record found with this ID"]);
        }
    } else {
        echo json_encode(["error" => "Failed to delete employee"]);
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
