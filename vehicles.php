<?php
require_once "config.php";

$search = isset($_GET['search']) ? $_GET['search'] : '';
$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : '';
$max_price = isset($_GET['max_price']) ? $_GET['max_price'] : '';

$sql = "SELECT * FROM vehicles WHERE available = 1";
$params = array();
$types = "";

if (!empty($search)) {
    $sql .= " AND (model LIKE ? OR description LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "ss";
}

if (!empty($min_price)) {
    $sql .= " AND price >= ?";
    $params[] = $min_price;
    $types .= "d";
}

if (!empty($max_price)) {
    $sql .= " AND price <= ?";
    $params[] = $max_price;
    $types .= "d";
}

$stmt = mysqli_prepare($conn, $sql);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Vehicles - BERUDI</title>
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
                        <a class="nav-link active" href="vehicles.php">Our Vehicles</a>
                    </li>
                    <?php
                    session_start();
                    if(isset($_SESSION['user_id'])) {
                        echo '<li class="nav-item"><a class="nav-link" href="reservations.php">My Reservations</a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>';
                    } else {
                        echo '<li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>';
                        echo '<li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-12">
                <form id="searchForm" class="card p-3">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control" id="search" name="search" placeholder="Search vehicles..." value="<?php echo htmlspecialchars($search); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="number" class="form-control" id="min_price" name="min_price" placeholder="Min. Price" value="<?php echo htmlspecialchars($min_price); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="number" class="form-control" id="max_price" name="max_price" placeholder="Max. Price" value="<?php echo htmlspecialchars($max_price); ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row" id="vehicleList">
            <?php while($vehicle = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="<?php echo htmlspecialchars($vehicle['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($vehicle['model']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($vehicle['model']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($vehicle['description']); ?></p>
                        <ul class="list-unstyled">
                            <li><strong>Year:</strong> <?php echo htmlspecialchars($vehicle['year']); ?></li>
                            <li><strong>Horsepower:</strong> <?php echo htmlspecialchars($vehicle['horsepower']); ?> HP</li>
                            <li><strong>Daily Rate:</strong> $<?php echo number_format($vehicle['price'], 2); ?></li>
                        </ul>
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <a href="reserve.php?id=<?php echo $vehicle['id']; ?>" class="btn btn-primary">Make Reservation</a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-secondary">Login to Reserve</a>
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
    $(document).ready(function() {
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: 'search_vehicles.php',
                type: 'GET',
                data: $(this).serialize(),
                success: function(response) {
                    $('#vehicleList').html(response);
                }
            });
        });
    });
    </script>
</body>
</html> 