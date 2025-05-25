<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true){
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

if(!isset($_POST['id']) || !isset($_POST['action'])) {
    echo json_encode(['success' => false, 'message' => 'Reservation ID and action are required.']);
    exit;
}

require_once "../config.php";

$reservation_id = $_POST['id'];
$action = $_POST['action'];

if($action === 'confirm') {
    $sql = "UPDATE reservations SET status = 'confirmed' WHERE id = ?";
    $success_message = 'Reservation confirmed successfully.';
} elseif($action === 'cancel') {
    $sql = "UPDATE reservations SET status = 'cancelled' WHERE id = ?";
    $success_message = 'Reservation cancelled successfully.';
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action.']);
    exit;
}

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $reservation_id);

if(mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => $success_message]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error occurred while processing reservation.']);
}

mysqli_close($conn);
?> 