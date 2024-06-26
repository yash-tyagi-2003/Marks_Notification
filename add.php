<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "yash";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// $sql="CREATE TABLE marks(
//     student_id INT NOT NULL PRIMARY KEY,
//     subject VARCHAR(20) NOT NULL,
//     marks DECIMAL(4,2) NOT NULL,
//     exam_date VARCHAR(10) NOT NULL
// )";

// $sql="CREATE TABLE parents(
//     student_id INT NOT NULL PRIMARY KEY,
//     name VARCHAR(20) NOT NULL,
//     phone INT8 NOT NULL
// )";

$sql="INSERT INTO parents VALUES(21011714,'VINESH',7906971021)";

if($conn->execute_query($sql))
echo "done";

?>