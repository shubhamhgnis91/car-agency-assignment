<?php
session_start();
require 'dbconn.php';

// Fetch all available cars
$stmt = $conn->prepare('SELECT id, model, vehicle_number, seating_capacity, rent_per_day FROM cars WHERE is_available = 1');
$stmt->execute();
$result = $stmt->get_result();
$cars = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Cars to Rent</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="dashboard.php">Dashboard</a>
        <a class="navbar-brand" href="..\index.php">Home</a>
    </nav>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Available Cars to Rent</h2>
        
        <?php
        // Display success message if it exists
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }

        // Display error message if it exists
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>

        <div class="row">
            <?php foreach ($cars as $car): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($car['model']); ?></h5>
                            <p class="card-text">Vehicle Number: <?php echo htmlspecialchars($car['vehicle_number']); ?></p>
                            <p class="card-text">Seating Capacity: <?php echo htmlspecialchars($car['seating_capacity']); ?></p>
                            <p class="card-text">Rent Per Day: ₹<?php echo htmlspecialchars($car['rent_per_day']); ?></p>
                            
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'customer'): ?>
                                <form action="rent_car.php" method="POST">
                                    <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">
                                    <div class="form-group">
                                        <label for="days">Number of Days</label>
                                        <select class="form-control" id="days" name="days" required>
                                            <?php for ($i = 1; $i <= 30; $i++): ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="start_date">Start Date</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block">Rent Car</button>
                                </form>
                            <?php elseif (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'agency'): ?>
                                <p class="text-danger">Agencies cannot rent cars.</p>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-primary btn-block">Login to Rent</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>