<?php

//CORS
header("Access-Control-Allow-Origin: http://front_v2.loc");
header("Access-Control-Allow-Methods: GET,POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { //pre-line request processing
    http_response_code(200);
    exit;
}

// database connection
global $conn;
require_once("db_conect.php");

// receives json
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// get data via URL and call a certain function depending on the received data
$action = $_GET['action'];

switch ($action) {
    case 'addItem':
        addItem($data, $conn);
        break;
    case 'changeItem':
        changeItem($data, $conn);
        break;
    case 'deleteItem':
        deleteItem($data, $conn);
        break;
    case 'getItems':
        getItems($conn);
        break;
    case 'login':
        login($data, $conn);
        break;
    case 'logout':
        logout($conn);
        break;
    case 'register':
        register($data, $conn);
        break;
    default:
        http_response_code(500);
        echo json_encode(['error' => 'Undefined action']);
        exit();
}

/**
 * The function adds a new element to the table of items in the database.
 * @param $data - a data array that contains information about the new item
 * @param $conn - database connection object
 * @return void
 */
function addItem($data, $conn): void
{
    $value_data = Strip_tags(htmlspecialchars($data['text']));

    $stmt = $conn->prepare("INSERT INTO items (text, checked) VALUES (?, 0)"); // prepared request
    $stmt->bind_param("s", $value_data);

    if ($stmt->execute()) {
        $id = $stmt->insert_id;
        http_response_code(200);
        echo json_encode(['id' => $id]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Server error']);
    }

    $stmt->close();
}

/**
 * The function updates the data in the database
 * @param $data - a data array that contains information about the new item
 * @param $conn - database connection object
 * @return void
 */
function changeItem($data, $conn): void
{
    $id = Strip_tags(htmlspecialchars($data["id"])); // The ID of the record to update
    $text = Strip_tags(htmlspecialchars($data["text"]));// New values to update
    $checked = Strip_tags(htmlspecialchars($data["checked"])); // New values to update

    $stmt = $conn->prepare("UPDATE items SET text = ?, checked = ? WHERE id = ?"); // prepared request
    $stmt->bind_param("ssi", $text, $checked, $id);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['ok' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Server error']);
    }

    $stmt->close();
}

/**
 * The function deletes an item from the database.
 * @param $data - a data array that contains information about the new item
 * @param mysqli $conn - database connection object
 * @return void
 */
function deleteItem($data, mysqli $conn): void
{
    $id = Strip_tags(htmlspecialchars($data["id"]));

    $stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['ok' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Server error']);
    }
    $stmt->close();
}

/**
 * The function is responsible for receiving all items from the items table in the database
 * and returning them in JSON format.
 * @param mysqli $conn - database connection object
 * @return void
 */
function getItems(mysqli $conn): void
{
    $sql = "SELECT * FROM items";
    $result = $conn->query($sql);

    $data = $result->fetch_all(MYSQLI_ASSOC);

    echo json_encode(['items' => $data]);;
}

/**
 * The function is responsible for the user authorization process
 * @param $data - a data array that contains information about the new item
 * @param mysqli $conn - database connection object
 * @return void
 */
function login($data, mysqli $conn): void
{
    session_start(); //session opening

    $login = $data["login"];
    $password = $data["pass"] . "ZxCvBn"; // add additional characters for security

    // If fields are empty
    if (empty($login) || empty($data["pass"])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
    }

    //Login and password validation
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $login) || !preg_match('/^[a-zA-Z0-9_]+$/', $password)) {
        http_response_code(409);
        echo json_encode(['error' => 'Login and password can only contain Latin letters, numbers and an underscore']);
    }

    //SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE login = ?"); // prepared request
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if (mysqli_num_rows($result) != 1) { //if the user is not registered
        http_response_code(401);
        exit(json_encode(['error' => 'User not found']));
    }

    $row = $result->fetch_assoc(); // convert the string into an associative array

    if (!password_verify($password, $row['pass'])) { //if the password hash does not match
        http_response_code(400);
        exit(json_encode(['error' => 'Incorrect password']));
    }

    try {
        $token = bin2hex(random_bytes(32));
        setcookie('auth_token', $token, time() + 3600, '/', '', true, true);// store the token in a cookie for 1 hour
        $_SESSION['auth_token'] = $token; // store the token in the session
        http_response_code(200);
        echo json_encode(['ok' => true]);

    } catch (\Random\RandomException $e) {
        error_log("Error generating random token: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Server error']);
    }

    $stmt->close();
}

/**
 *The function terminates the user's session.
 * @return void
 */
function logout(): void
{
    try {
        session_start(); // check if a session exists
        session_unset(); // clear all session variables
        session_destroy(); // destroy the session
        echo json_encode(['ok' => 'true']);

    } catch (Exception $e) {
        error_log("Error destroy the session: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Server error']);
    }
}

/**
 * The function is responsible for the user registration process
 * @param $data - a data array that contains information about the new item
 * @param mysqli $conn - database connection object
 * @return void
 */
function register($data, mysqli $conn): void
{
    $login = $data["login"];
    $password = $data["pass"] . "ZxCvBn"; // add additional characters for security

    // If fields are empty
    if (empty($login) || empty($data["pass"])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    //Login and password validation
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $login) || !preg_match('/^[a-zA-Z0-9_]+$/', $password)) {
        http_response_code(409);
        echo json_encode(['error' => 'Login and password can only contain Latin letters, numbers and an underscore']);
    }

    // Preparation and execution of the request
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE login = ?");
    $stmt->bind_param("s", $login);

    if (!$stmt->execute()) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }

    $result = $stmt->get_result();
    $count = $result->fetch_column();

    if ($count > 0) {
        http_response_code(409);
        echo json_encode(['error' => 'A user with this login already exists']);
    }
    $hash_password = password_hash($password, PASSWORD_DEFAULT); // Hashed password

    //Add a user to the database
    $stmt = $conn->prepare("INSERT INTO users (login, pass) VALUES (?, ?)"); // prepared request
    $stmt->bind_param("ss", $login, $hash_password);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['ok' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }

    $stmt->close();
}

$conn->close(); // closing the connection