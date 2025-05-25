<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    echo json_encode(['success' => false, 'error' => 'Login required.']);
    exit;
}

require_once "config.php";

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reservation_id'])){
    $reservation_id = $_POST['reservation_id'];
    $user_id = $_SESSION["id"];
    
    $check_sql = "SELECT id FROM reservations WHERE id = ? AND user_id = ?";
    if($check_stmt = mysqli_prepare($conn, $check_sql)){
        mysqli_stmt_bind_param($check_stmt, "ii", $reservation_id, $user_id);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        
        if(mysqli_stmt_num_rows($check_stmt) == 1){
            mysqli_stmt_close($check_stmt);
            
            $cancel_sql = "UPDATE reservations SET status = 'cancelled' WHERE id = ? AND user_id = ?";
            if($cancel_stmt = mysqli_prepare($conn, $cancel_sql)){
                mysqli_stmt_bind_param($cancel_stmt, "ii", $reservation_id, $user_id);
                
                if(mysqli_stmt_execute($cancel_stmt)){
                    echo json_encode(['success' => true, 'message' => 'Reservation cancelled successfully.']);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Error occurred while cancelling reservation.']);
                }
                mysqli_stmt_close($cancel_stmt);
            } else {
                echo json_encode(['success' => false, 'error' => 'Database error.']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Reservation not found or does not belong to you.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Database error.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
}

mysqli_close($conn);
?> 