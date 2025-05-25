<?php
session_start();
require_once "../config.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true){
    exit('Access denied.');
}

if(!isset($_GET['user_id'])) {
    exit('User ID is required.');
}

$user_id = $_GET['user_id'];

$user_sql = "SELECT username FROM users WHERE id = ?";
$user_stmt = mysqli_prepare($conn, $user_sql);
mysqli_stmt_bind_param($user_stmt, "i", $user_id);
mysqli_stmt_execute($user_stmt);
$user_result = mysqli_stmt_get_result($user_stmt);
$user = mysqli_fetch_assoc($user_result);

$sql = "SELECT r.*, v.model, v.image_url 
        FROM reservations r 
        JOIN vehicles v ON r.vehicle_id = v.id 
        WHERE r.user_id = ? 
        ORDER BY r.created_at DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<h5>Reservations for: <?php echo htmlspecialchars($user['username']); ?></h5>

<?php if(mysqli_num_rows($result) > 0): ?>
    <div class="table-responsive">
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Vehicle</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                <?php while($reservation = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($reservation['model']); ?></td>
                    <td><?php echo date('m/d/Y', strtotime($reservation['start_date'])); ?></td>
                    <td><?php echo date('m/d/Y', strtotime($reservation['end_date'])); ?></td>
                    <td>$<?php echo number_format($reservation['total_price'], 2); ?></td>
                    <td>
                        <?php if($reservation['status'] == 'pending'): ?>
                            <span class="badge badge-warning">Pending</span>
                        <?php elseif($reservation['status'] == 'confirmed'): ?>
                            <span class="badge badge-success">Confirmed</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Cancelled</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo date('m/d/Y H:i', strtotime($reservation['created_at'])); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        This user has no reservations yet.
    </div>
<?php endif; ?> 