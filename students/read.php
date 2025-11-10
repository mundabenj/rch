<?php
// JSON headers
header("Content-Type: application/json; charset=UTF-8"); // define content type as JSON
header("Access-Control-Allow-Origin: *"); // allow access from any origin
header("Access-Control-Allow-Methods: GET"); // Only allow GET requests and not POST, PUT, DELETE etc.
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Origin, Access-Control-Allow-Methods, Access-Control-Allow-Headers, Authorization, X-Requested-With"); // Allow specific headers

// include database and object files
require_once '../inc/pdo.php';

// verify the request method is GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    $response = [
        'status' => http_response_code(405), // Method Not Allowed
        'message' => 'Method Not Allowed. Use GET.'
    ];
    echo json_encode($response);
    exit();
}

// Use if condition to fetch single or all students
if(isset($_GET['userId']) && !empty($_GET['userId'])) {
    // fetch single student based on userId
    $userId = intval($_GET['userId']);

    // prepare select statement
    $sql = "SELECT fullname, email FROM users WHERE userId = :userId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();

    // count number of rows
    $rowCount = $stmt->rowCount();
    if($rowCount > 0) {
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        $response = [
            'status' => http_response_code(200), // OK
            'message'=> 'Student retrieved successfully',
            'data' => $student
        ];
        echo json_encode($response);
        exit();
    } else {
        $response = [
            'status' => http_response_code(404), // Not Found
            'message' => 'Student not found.'
        ];
        echo json_encode($response);
        exit();
    }
} else {
    // fetch all students
    $sql = "SELECT fullname, email FROM users";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($students) {
        $response = [
            'status' => http_response_code(200), // OK
            'message'=> 'Students retrieved successfully',
            'data' => $students
        ];
        echo json_encode($response);
    } else {
        $response = [
            'status' => http_response_code(404), // Not Found
            'message' => 'No students found.'
        ];
        echo json_encode($response);
    }
}