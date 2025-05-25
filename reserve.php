<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";

if(!isset($_GET['id'])) {
    header("location: vehicles.php");
    exit;
}

$vehicle_id = $_GET['id'];
$sql = "SELECT * FROM vehicles WHERE id = ? AND available = 1";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $vehicle_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) == 0) {
    header("location: vehicles.php");
    exit;
}

$vehicle = mysqli_fetch_assoc($result);

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $interval = $start->diff($end);
    $days = $interval->days;
    
    $total_price = $days * $vehicle['price'];
    
    $sql = "INSERT INTO reservations (user_id, vehicle_id, start_date, end_date, total_price) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iissd", $_SESSION['id'], $vehicle_id, $start_date, $end_date, $total_price);
    
    if(mysqli_stmt_execute($stmt)) {
        header("location: index.php");
        exit;
    } else {
        $error = "An error occurred while creating the reservation.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Reservation - BERUDI</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">BERUDI</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="vehicles.php">Our Vehicles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="reservations.php">My Reservations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Make Reservation</h2>
                    </div>
                    <div class="card-body">
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <img src="<?php echo htmlspecialchars($vehicle['image_url']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($vehicle['model']); ?>">
                            </div>
                            <div class="col-md-6">
                                <h4><?php echo htmlspecialchars($vehicle['model']); ?></h4>
                                <p><?php echo htmlspecialchars($vehicle['description']); ?></p>
                                <ul class="list-unstyled">
                                    <li><strong>Year:</strong> <?php echo htmlspecialchars($vehicle['year']); ?></li>
                                    <li><strong>Horsepower:</strong> <?php echo htmlspecialchars($vehicle['horsepower']); ?> HP</li>
                                    <li><strong>Daily Rate:</strong> $<?php echo number_format($vehicle['price'], 2); ?></li>
                                </ul>
                            </div>
                        </div>

                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $vehicle_id); ?>" method="post">
                            <div class="form-group">
                                <label>Start Date</label>
                                <input type="date" name="start_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="form-group">
                                <label>End Date</label>
                                <input type="date" name="end_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary">Make Reservation</button>
                                <a href="vehicles.php" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        $('input[type="date"]').on('change', function() {
            var startDate = new Date($('input[name="start_date"]').val());
            var endDate = new Date($('input[name="end_date"]').val());
            
            if(endDate < startDate) {
                alert('End date cannot be before start date!');
                $('input[name="end_date"]').val('');
            }
        });
    });
    </script>
</body>
</html> 