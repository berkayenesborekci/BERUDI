<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";

$user_id = $_SESSION["id"];
$sql = "SELECT r.*, v.model, v.image_url, v.price 
        FROM reservations r 
        JOIN vehicles v ON r.vehicle_id = v.id 
        WHERE r.user_id = ? AND r.status != 'cancelled'
        ORDER BY r.created_at DESC";

$reservations = array();
if($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    while($row = mysqli_fetch_assoc($result)) {
        $reservations[] = $row;
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations - BERUDI</title>
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
                        <a class="nav-link" href="vehicles.php">Vehicles</a>
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
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-4">My Reservations</h2>
            </div>
        </div>

        <div class="row">
            <?php if(!empty($reservations)): ?>
                <?php foreach($reservations as $reservation): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <?php if($reservation['image_url']): ?>
                                <img src="<?php echo htmlspecialchars($reservation['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($reservation['model']); ?>" style="height: 200px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($reservation['model']); ?></h5>
                                <p class="card-text">
                                    <strong>Start Date:</strong> <?php echo date('m/d/Y', strtotime($reservation['start_date'])); ?><br>
                                    <strong>End Date:</strong> <?php echo date('m/d/Y', strtotime($reservation['end_date'])); ?><br>
                                    <strong>Total Amount:</strong> $<?php echo number_format($reservation['total_price'], 2); ?><br>
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
                                    <button class="btn btn-danger btn-sm" onclick="cancelReservation(<?php echo $reservation['id']; ?>)">Cancel Reservation</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-md-12">
                    <div class="alert alert-info">
                        You don't have any reservations yet. <a href="vehicles.php">Browse our vehicles</a>.
                    </div>
                </div>
            <?php endif; ?>
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
                        alert('An error occurred while canceling the reservation.');
                    }
                }, 'json');
            }
        }
    </script>
</body>
</html> 