<?php
session_start();
require_once "config.php";

$sql = "SELECT * FROM vehicles WHERE available = 1 ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

$user_reservations = array();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $user_id = $_SESSION["id"];
    $res_sql = "SELECT r.*, v.model, v.image_url, v.price 
                FROM reservations r 
                JOIN vehicles v ON r.vehicle_id = v.id 
                WHERE r.user_id = ? AND r.status != 'cancelled'
                ORDER BY r.created_at DESC";
    
    if($res_stmt = mysqli_prepare($conn, $res_sql)) {
        mysqli_stmt_bind_param($res_stmt, "i", $user_id);
        mysqli_stmt_execute($res_stmt);
        $res_result = mysqli_stmt_get_result($res_stmt);
        
        while($row = mysqli_fetch_assoc($res_result)) {
            $user_reservations[] = $row;
        }
        mysqli_stmt_close($res_stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BERUDI - Premium Audi Sport Car Rental</title>
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
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                        <li class="nav-item">
                            <span class="nav-link">Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</span>
                        </li>
                        <?php if(isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] === true): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin">Admin Panel</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
            <div class="row mb-5">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>My Reservations</h4>
                        </div>
                        <div class="card-body">
                            <?php if(!empty($user_reservations)): ?>
                                <div class="row">
                                    <?php foreach($user_reservations as $reservation): ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title"><?php echo htmlspecialchars($reservation['model']); ?></h5>
                                                    <p class="card-text">
                                                        <strong>Start Date:</strong> <?php echo date('m/d/Y', strtotime($reservation['start_date'])); ?><br>
                                                        <strong>End Date:</strong> <?php echo date('m/d/Y', strtotime($reservation['end_date'])); ?><br>
                                                        <strong>Total:</strong> $<?php echo number_format($reservation['total_price'], 2); ?><br>
                                                        <strong>Status:</strong> 
                                                        <?php if($reservation['status'] == 'pending'): ?>
                                                            <span class="badge badge-warning">Pending</span>
                                                        <?php elseif($reservation['status'] == 'confirmed'): ?>
                                                            <span class="badge badge-success">Confirmed</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-danger">Cancelled</span>
                                                        <?php endif; ?>
                                                    </p>
                                                    <?php if($reservation['status'] == 'pending'): ?>
                                                        <button class="btn btn-sm btn-danger" onclick="cancelReservation(<?php echo $reservation['id']; ?>)">Cancel</button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">You don't have any reservations yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12 mb-4">
                <h1 class="text-center">Premium Audi Sport Car Rental</h1>
                <p class="text-center">Rent the most prestigious Audi sports cars for your special occasions.</p>
            </div>
        </div>

        <div class="row">
            <?php while($vehicle = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="<?php echo htmlspecialchars($vehicle['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($vehicle['model']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($vehicle['model']); ?></h5>
                        <p class="card-text">
                            <strong>Year:</strong> <?php echo $vehicle['year']; ?><br>
                            <strong>Horsepower:</strong> <?php echo $vehicle['horsepower']; ?> HP<br>
                            <strong>Daily Rate:</strong> $<?php echo number_format($vehicle['price'], 2); ?>
                        </p>
                        <p class="card-text"><?php echo htmlspecialchars($vehicle['description']); ?></p>
                        <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                            <a href="reserve.php?id=<?php echo $vehicle['id']; ?>" class="btn btn-primary">Make Reservation</a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-primary">Login to Reserve</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function cancelReservation(reservationId) {
            if(confirm('Are you sure you want to cancel this reservation?')) {
                $.post('cancel_reservation.php', {
                    reservation_id: reservationId
                }, function(response) {
                    if(response.success) {
                        location.reload();
                    } else {
                        alert('Error cancelling reservation: ' + (response.error || 'Unknown error'));
                    }
                }, 'json');
            }
        }
    </script>
</body>
</html> 