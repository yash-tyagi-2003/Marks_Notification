<?php
require "./twilio-php-app/twilio-php-app/twilio-php-app/vendor/autoload.php";
use Twilio\Rest\Client;

$student_id = $_POST['student_id'];
$subject = $_POST['subject'];
$marks = $_POST['marks'];
$exam_date = $_POST['exam_date'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "yash";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Prepare and bind SQL statement using prepared statement
$sql = "INSERT INTO marks (student_id, subject, marks, exam_date) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isds", $student_id, $subject, $marks, $exam_date);

if ($stmt->execute()) {
    // Fetch parent phone number
    $parent_sql = "SELECT phone FROM Parents WHERE student_id = ?";
    $parent_stmt = $conn->prepare($parent_sql);
    $parent_stmt->bind_param("i", $student_id);
    $parent_stmt->execute();
    $parent_result = $parent_stmt->get_result();

    if ($parent_result->num_rows > 0) {
        $row = $parent_result->fetch_assoc();
        $phone_number = $parent_result['phone'];
        $message = "Your child has received $marks marks in $subject.";

        // Send SMS using Twilio
        $sid = 'SID';  // Replace with your actual Account SID
        $token = 'TOKEN';  // Replace with your actual Auth Token
        $twilio_number = "NUMBER";

        try {
            $client = new Client($sid, $token);
            $client->messages->create(
                $phone_number,
                array(
                    'from' => $twilio_number,
                    'body' => $message
                )
            );

            echo json_encode(['success' => true, 'message' => 'Marks submitted and SMS sent!']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error sending SMS: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => true, 'message' => 'Marks submitted but parent phone number not found!']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error inserting marks: ' . $stmt->error]);
}

// Close connections
$stmt->close();
$parent_stmt->close();
$conn->close();
?>
