<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true){
    header("location: ../login.php");
    exit;
}

require_once "../config.php";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $price = $_POST['price'];
    $horsepower = $_POST['horsepower'];
    $description = $_POST['description'];
    $image_url = $_POST['image_url'];
    $available = isset($_POST['available']) ? 1 : 0;
    
    $sql = "UPDATE vehicles SET model = ?, year = ?, price = ?, horsepower = ?, description = ?, image_url = ?, available = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sidissii", $model, $year, $price, $horsepower, $description, $image_url, $available, $id);
    
    if(mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error occurred while updating vehicle.']);
    }
    exit;
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM vehicles WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if($vehicle = mysqli_fetch_assoc($result)) {
        echo json_encode($vehicle);
    } else {
        echo json_encode(['error' => 'Vehicle not found.']);
    }
    exit;
}
?> 