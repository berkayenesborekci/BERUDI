<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true){
    header("location: ../login.php");
    exit;
}

require_once "../config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add') {
            $model = $_POST['model'];
            $year = $_POST['year'];
            $horsepower = $_POST['horsepower'];
            $price = $_POST['price'];
            $description = $_POST['description'];
            $image_url = $_POST['image_url'];
            $available = isset($_POST['available']) ? 1 : 0;
            
            $sql = "INSERT INTO vehicles (model, year, horsepower, price, description, image_url, available) VALUES (?, ?, ?, ?, ?, ?, ?)";
            if($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "siidssi", $model, $year, $horsepower, $price, $description, $image_url, $available);
                if(mysqli_stmt_execute($stmt)) {
                    echo "<div class='alert alert-success'>Vehicle added successfully!</div>";
                } else {
                    echo "<div class='alert alert-danger'>Error adding vehicle: " . mysqli_error($conn) . "</div>";
                }
                mysqli_stmt_close($stmt);
            }
        } else if ($_POST['action'] == 'edit') {
            $id = $_POST['vehicle_id'];
            $model = $_POST['model'];
            $year = $_POST['year'];
            $horsepower = $_POST['horsepower'];
            $price = $_POST['price'];
            $description = $_POST['description'];
            $image_url = $_POST['image_url'];
            $available = isset($_POST['available']) ? 1 : 0;
            
            $sql = "UPDATE vehicles SET model=?, year=?, horsepower=?, price=?, description=?, image_url=?, available=? WHERE id=?";
            if($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "siidssii", $model, $year, $horsepower, $price, $description, $image_url, $available, $id);
                if(mysqli_stmt_execute($stmt)) {
                    echo "<div class='alert alert-success'>Vehicle updated successfully!</div>";
                } else {
                    echo "<div class='alert alert-danger'>Error updating vehicle: " . mysqli_error($conn) . "</div>";
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
}

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM vehicles WHERE id = ?";
    if($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        if(mysqli_stmt_execute($stmt)) {
            echo "<div class='alert alert-success'>Vehicle deleted successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error deleting vehicle: " . mysqli_error($conn) . "</div>";
        }
        mysqli_stmt_close($stmt);
    }
}

$sql = "SELECT * FROM vehicles ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Management - BERUDI Admin</title>
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
                        <a class="nav-link" href="index.php">Admin Panel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="vehicles.php">Vehicles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12 mb-4">
                <h2>Vehicle Management</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVehicleModal">Add New Vehicle</button>
            </div>
        </div>

        <?php if(isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Model</th>
                                        <th>Year</th>
                                        <th>Price</th>
                                        <th>Horsepower</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($vehicle = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo $vehicle['id']; ?></td>
                                        <td>
                                            <img src="../<?php echo htmlspecialchars($vehicle['image_url']); ?>" class="img-thumbnail" style="width: 100px;" alt="Vehicle">
                                        </td>
                                        <td><?php echo htmlspecialchars($vehicle['model']); ?></td>
                                        <td><?php echo $vehicle['year']; ?></td>
                                        <td>$<?php echo number_format($vehicle['price'], 2); ?></td>
                                        <td><?php echo $vehicle['horsepower']; ?> HP</td>
                                        <td>
                                            <?php if($vehicle['available']): ?>
                                                <span class="badge badge-success">Available</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Not Available</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-info btn-sm edit-vehicle" data-id="<?php echo $vehicle['id']; ?>">Edit</button>
                                            <a href="?delete=<?php echo $vehicle['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this vehicle?')">Delete</a>
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

    <!-- Add Vehicle Modal -->
    <div class="modal fade" id="addVehicleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Vehicle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <input type="hidden" name="action" value="add">
                        <div class="form-group">
                            <label>Model</label>
                            <input type="text" name="model" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Year</label>
                            <input type="number" name="year" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Daily Price ($)</label>
                            <input type="number" name="price" class="form-control" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Horsepower</label>
                            <input type="number" name="horsepower" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Image URL</label>
                            <input type="url" name="image_url" class="form-control" required>
                        </div>
                        <div class="form-group text-center mt-3">
                            <button type="submit" class="btn btn-primary">Add Vehicle</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Vehicle Modal -->
    <div class="modal fade" id="editVehicleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Vehicle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editVehicleForm" method="post">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="form-group">
                            <label>Model</label>
                            <input type="text" name="model" id="edit_model" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Year</label>
                            <input type="number" name="year" id="edit_year" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Daily Price ($)</label>
                            <input type="number" name="price" id="edit_price" class="form-control" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Horsepower</label>
                            <input type="number" name="horsepower" id="edit_horsepower" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Image URL</label>
                            <input type="url" name="image_url" id="edit_image_url" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" name="available" id="edit_available" class="form-check-input">
                                <label class="form-check-label">Available</label>
                            </div>
                        </div>
                        <div class="form-group text-center mt-3">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.edit-vehicle').click(function() {
                var id = $(this).data('id');
                
                $.get('edit_vehicle.php', {id: id}, function(response) {
                    if(response.error) {
                        alert(response.error);
                        return;
                    }
                    
                    $('#edit_id').val(response.id);
                    $('#edit_model').val(response.model);
                    $('#edit_year').val(response.year);
                    $('#edit_price').val(response.price);
                    $('#edit_horsepower').val(response.horsepower);
                    $('#edit_description').val(response.description);
                    $('#edit_image_url').val(response.image_url);
                    $('#edit_available').prop('checked', response.available == 1);
                    
                    $('#editVehicleModal').modal('show');
                }, 'json');
            });
        });
    </script>
</body>
</html> 