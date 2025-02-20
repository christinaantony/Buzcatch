<?php
session_start();
include '../db.php';
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Methods: POST"); 
header("Access-Control-Allow-Headers: Content-Type, Access-Control - Allow-Headers, Authorization, X-Request-with"); // Allow necessary headers

if($requestMethod == "POST") {

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'];
$password = $data['password'];

$sql = "SELECT * FROM students WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    echo json_encode(["success" => true, "message" => "Login successful"]);
} else {
    echo json_encode(["error" => "Invalid email or password"]);
}
}
else 
{
 $data = [
    'status' => 405,
    'message' => $requestMethod. 'Method Not Allowed',
 ];
 header("HTTP/1.0 405 Method Not Allowed");
 echo json_encode($data);
}
?>
