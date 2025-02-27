<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_dashboard";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
$conn->query($sql);
$conn->select_db($dbname);

// Function to create a table
function createTable($conn, $sql, $tableName) {
    if ($conn->query($sql) === TRUE) {
        echo "$tableName table created successfully\n";
    } else {
        echo "Error creating $tableName table: " . $conn->error . "\n";
    }
}

// Create company table
$sql = "CREATE TABLE company (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(255) NOT NULL,
    company_address VARCHAR(255) NOT NULL,
    place VARCHAR(100) NOT NULL,
    email_id VARCHAR(255) NOT NULL,
    mobile_number VARCHAR(15) NOT NULL,
    userid INT NOT NULL,
    ip_address VARCHAR(50) NOT NULL,
    thumbnail_image VARCHAR(255) NOT NULL,
    joining_date DATE NOT NULL DEFAULT CURRENT_DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
createTable($conn, $sql, "company");

// Create employee table
$sql = "CREATE TABLE employee (
    id INT PRIMARY KEY AUTO_INCREMENT,
    companyid INT NOT NULL,
    employee_id VARCHAR(50) NOT NULL UNIQUE,
    employee_name VARCHAR(100) NOT NULL,
    employee_address VARCHAR(255) NOT NULL,
    place VARCHAR(100) NOT NULL,
    mail_id VARCHAR(100) NOT NULL,
    pincode VARCHAR(10) NOT NULL,
    mobile_number VARCHAR(15) NOT NULL,
    thumbnail_image VARCHAR(255),
    FOREIGN KEY (companyid) REFERENCES company(id) ON DELETE CASCADE
)";
createTable($conn, $sql, "employee");

// Create members table
$sql = "CREATE TABLE members (
    id INT PRIMARY KEY AUTO_INCREMENT,
    companyid INT NOT NULL,
    account_id INT NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    mobile_number VARCHAR(15) NOT NULL,
    email_id VARCHAR(100) NOT NULL,
    joined_date DATE NOT NULL,
    thumbnail_image VARCHAR(255),
    FOREIGN KEY (companyid) REFERENCES company(id) ON DELETE CASCADE
)";
createTable($conn, $sql, "members");

// Create MDS table
$sql = "CREATE TABLE mds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mds_id INT NOT NULL UNIQUE,
    companyid INT NOT NULL,
    mds_name VARCHAR(100) NOT NULL,
    total_salary DECIMAL(10,2) NOT NULL,
    starting_date DATE NOT NULL,
    number_of_installments INT NOT NULL,
    end_date DATE NOT NULL,
    FOREIGN KEY (companyid) REFERENCES company(id) ON DELETE CASCADE
)";
createTable($conn, $sql, "mds");

// Create mdsmembers table
$sql = "CREATE TABLE IF NOT EXISTS mdsmembers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    companyid INT NOT NULL,
    mds_id INT(11) NOT NULL,
    memberid INT NOT NULL,
    joining_date DATE NOT NULL,
    FOREIGN KEY (companyid) REFERENCES company(id) ON DELETE CASCADE,
    FOREIGN KEY (mds_id) REFERENCES mds(mds_id) ON DELETE CASCADE,
    FOREIGN KEY (memberid) REFERENCES members(id) ON DELETE CASCADE
)";
createTable($conn, $sql, "mdsmembers");

//payment table
$sql= "CREATE TABLE IF NOT EXISTS payment (
    id INT PRIMARY KEY, -- Not auto-increment
    installment_id INT NOT NULL,
    memberid INT NOT NULL,
    companyid INT NOT NULL,
    mds_id INT NOT NULL,
    userid INT NOT NULL,
    created_at DATE NOT NULL,
    paid_date DATE NOT NULL,
    paid_amount DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (memberid) REFERENCES members(id) ON DELETE CASCADE,
    FOREIGN KEY (companyid) REFERENCES company(id) ON DELETE CASCADE,
    FOREIGN KEY (mds_id) REFERENCES mds(mds_id) ON DELETE CASCADE
)";
createTable($conn, $sql, "payment");


$conn->close();
?>
