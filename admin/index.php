<?php
session_start();
require_once "../includes/functions.php";
require_once "../config.php";

checkAdminAuth();

$user_count_sql = "SELECT COUNT(*) as count FROM users";
$user_count_result = mysqli_query($conn, $user_count_sql);
$user_count = mysqli_fetch_assoc($user_count_result)['count'];

$vehicle_count_sql = "SELECT COUNT(*) as count FROM vehicles";
$vehicle_count_result = mysqli_query($conn, $vehicle_count_sql);
$vehicle_count = mysqli_fetch_assoc($vehicle_count_result)['count'];

$reservation_count_sql = "SELECT COUNT(*) as count FROM reservations";
$reservation_count_result = mysqli_query($conn, $reservation_count_sql);
$reservation_count = mysqli_fetch_assoc($reservation_count_result)['count'];

$recent_reservations_sql = "SELECT r.*, u.username, v.model 
                           FROM reservations r 
                           JOIN users u ON r.user_id = u.id 
                           JOIN vehicles v ON r.vehicle_id = v.id 
                           ORDER BY r.created_at DESC 
                           LIMIT 10";
$recent_reservations_result = mysqli_query($conn, $recent_reservations_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - BERUDI</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">BERUDI</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Admin Panel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <?php displayMessage(); ?>
        
        <div class="row">
            <div class="col-md-12 mb-4">
                <h2>Admin Panel</h2>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text display-4"><?php echo $user_count; ?></p>
                        <a href="users.php" class="btn btn-light">View Users</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Vehicles</h5>
                        <p class="card-text display-4"><?php echo $vehicle_count; ?></p>
                        <a href="vehicles.php" class="btn btn-light">View Vehicles</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Reservations</h5>
                        <p class="card-text display-4"><?php echo $reservation_count; ?></p>
                        <a href="reservations.php" class="btn btn-light">View Reservations</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Reservations</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Vehicle</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($reservation = mysqli_fetch_assoc($recent_reservations_result)): ?>
                                    <tr>
                                        <td><?php echo $reservation['id']; ?></td>
                                        <td><?php echo htmlspecialchars($reservation['username']); ?></td>
                                        <td><?php echo htmlspecialchars($reservation['model']); ?></td>
                                        <td><?php echo formatDate($reservation['start_date']); ?></td>
                                        <td><?php echo formatDate($reservation['end_date']); ?></td>
                                        <td><?php echo formatPrice($reservation['total_price']); ?></td>
                                        <td><?php echo getStatusBadge($reservation['status']); ?></td>
                                        <td>
                                            <?php if($reservation['status'] == 'pending'): ?>
                                                <button class="btn btn-success btn-sm manage-reservation" data-id="<?php echo $reservation['id']; ?>" data-action="confirm">Confirm</button>
                                                <button class="btn btn-danger btn-sm manage-reservation" data-id="<?php echo $reservation['id']; ?>" data-action="cancel">Cancel</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.manage-reservation').on('click', function() {
            var reservationId = $(this).data('id');
            var action = $(this).data('action');
            var actionText = action === 'confirm' ? 'confirm' : 'cancel';
            
            if(confirm('Are you sure you want to ' + actionText + ' this reservation?')) {
                $.post('manage_reservation.php', {
                    id: reservationId,
                    action: action
                }, function(response) {
                    if(response.success) {
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                }, 'json');
            }
        });
    });
    </script>
</body>
</html> 
