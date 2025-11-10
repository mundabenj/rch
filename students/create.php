<?php
// JSON headers
header("Content-Type: application/json; charset=UTF-8"); // define content type as JSON
header("Access-Control-Allow-Origin: *"); // allow access from any origin
header("Access-Control-Allow-Methods: POST"); // Only allow POST requests and not GET, PUT, DELETE etc.
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Origin, Access-Control-Allow-Methods, Access-Control-Allow-Headers, Authorization, X-Requested-With"); // Allow specific headers

// include database and object files
require_once '../inc/pdo.php';

// verify the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $data = [
        'status' => http_response_code(405), // Method Not Allowed
        'message' => 'Method Not Allowed. Use POST.'
    ];
    echo json_encode($data);
    exit();
}

// get raw data from the request body
$requestBody = file_get_contents("php://input"); // read the raw input data

// decode JSON data
$JSONData = json_decode($requestBody, true); // decode the JSON data

// retrieve data from JSON or form-data
$FROMData = [
    'fullname' => trim($_POST['fullname'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
];

// Determine which data to use: JSON data or form-data (prioritize JSON data if available)
if(!empty($JSONData)) {
    $StudentData = $JSONData;
} else {
    $StudentData = $FROMData;
}

if(empty($StudentData['fullname']) || empty($StudentData['email'])) {
    $response = [
        'status' => http_response_code(400), // Bad Request
        'message' => 'Incomplete data. Full name, email are required.'
    ];
    echo json_encode($response);
    exit();
}

// Sanitize input data
$StudentData['fullname'] = addslashes($StudentData['fullname']);
$StudentData['email'] = filter_var($StudentData['email'], FILTER_SANITIZE_EMAIL);

// prepare an insert statement
try {
    $sql = "INSERT INTO users (fullname, email) VALUES (:fullname, :email)";
    $stmt = $pdo->prepare($sql);

    // bind parameters
    $stmt->bindParam(':fullname', $StudentData['fullname'], PDO::PARAM_STR);
    $stmt->bindParam(':email', $StudentData['email'], PDO::PARAM_STR);

    // execute the statement
    if($stmt->execute()) {
        $response = [
            'status' => http_response_code(201), // Created
            'message' => 'Student created successfully.',
            'data' => [
                'userId' => $pdo->lastInsertId(),
                'fullname' => $StudentData['fullname'],
                'email' => $StudentData['email']
            ]
        ];
        print json_encode($response);
    } else {
        $response = [
            'status' => http_response_code(500), // Internal Server Error
            'message' => 'Failed to create student.'
        ];
        print json_encode($response);
    }
} catch (PDOException $e) {
    $response = [
        'status' => http_response_code(500), // Internal Server Error
        'message' => 'Database error: ' . $e->getMessage()
    ];
    print json_encode($response);
}