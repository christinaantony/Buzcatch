<?php
require 'db.php'; // Database connection

header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are provided
    if (
        empty($_POST['companyid']) ||
        empty($_POST['employee_id']) ||
        empty($_POST['employee_name']) ||
        empty($_POST['employee_address']) ||
        empty($_POST['place']) ||
        empty($_POST['mail_id']) ||
        empty($_POST['pincode']) ||
        empty($_POST['mobile_number'])
    ) {
        echo json_encode(["error" => "All fields are required"]);
        exit;
    }

    // Get max id + 1
    $idQuery = "SELECT COALESCE(MAX(id), 0) + 1 AS new_id FROM employee";
    $idResult = $conn->query($idQuery);
    $row = $idResult->fetch_assoc();
    $new_id = $row['new_id'];

    // Sanitize input
    $companyid = trim($_POST['companyid']);
    $employee_id = trim($_POST['employee_id']);
    $employee_name = trim($_POST['employee_name']);
    $employee_address = trim($_POST['employee_address']);
    $place = trim($_POST['place']);
    $mail_id = trim($_POST['mail_id']);
    $pincode = trim($_POST['pincode']);
    $mobile_number = trim($_POST['mobile_number']);
    
    // Handle file upload if provided
    $thumbnail_image = null;
    if (isset($_FILES['thumbnail_image']) && $_FILES['thumbnail_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = "uploads/";
        $filename = uniqid() . "_" . basename($_FILES["thumbnail_image"]["name"]);
        $target_file = $upload_dir . $filename;

        if (move_uploaded_file($_FILES["thumbnail_image"]["tmp_name"], $target_file)) {
            $thumbnail_image = $filename;
        } else {
            echo json_encode(["error" => "Failed to upload file"]);
            exit;
        }
    }

    // Insert data
    $sql = "INSERT INTO employee (id, companyid, employee_id, employee_name, employee_address, place, mail_id, pincode, mobile_number, thumbnail_image) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissssssss", $new_id, $companyid, $employee_id, $employee_name, $employee_address, $place, $mail_id, $pincode, $mobile_number, $thumbnail_image);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Employee added successfully", "id" => $new_id]);
    } else {
        echo json_encode(["error" => "Failed to insert employee"]);
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
