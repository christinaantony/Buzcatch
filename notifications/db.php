<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "student_dashboard";
$conn = new mysqli($host,$username,$password,$database);
if($conn -> connect_error) {
    die(json_encode(["error" => "Connection failed"]));
}
?>