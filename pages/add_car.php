<?php

session_start();

// Check if the user is logged in and is an agency
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'agency') {

    header('Location: login.php');    
    exit();
}

require 'dbconn.php';

$vehicle_model = $vehicle_number = $seating_capacity = $rent_per_day = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $vehicle_model = $_POST['vehicle_model'];
    $vehicle_number = $_POST['vehicle_number'];
    $seating_capacity = $_POST['seating_capacity'];
    $rent_per_day = $_POST['rent_per_day'];

    $stmt = $conn->prepare('INSERT INTO cars (agency_id, model, vehicle_number, seating_capacity, rent_per_day) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('sssii', $_SESSION['user_id'], $vehicle_model, $vehicle_number, $seating_capacity, $rent_per_day);

    if ($stmt->execute()) {
        
        $_SESSION['success'] = 'Car added successfully.';
        header('Location: add_car.php');
        exit();
    } else {
        $_SESSION['error'] = 'Failed to add car. Please try again.';
    }


    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Car - Car Rental Agency</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Add Car</h2>

                <?php

                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']);
                }


                if (isset($_SESSION['success'])) {
                    echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                    unset($_SESSION['success']);
                }
                ?>

                <form action="add_car.php" method="POST">
                    <div class="form-group">
                        <label for="vehicle_model">Vehicle Model</label>
                        <input type="text" class="form-control" id="vehicle_model" name="vehicle_model" required>
                    </div>
                    <div class="form-group">
                        <label for="vehicle_number">Vehicle Number</label>
                        <input type="text" class="form-control" id="vehicle_number" name="vehicle_number" required>
                    </div>
                    <div class="form-group">
                        <label for="seating_capacity">Seating Capacity</label>
                        <input type="number" class="form-control" id="seating_capacity" name="seating_capacity" required>
                    </div>
                    <div class="form-group">
                        <label for="rent_per_day">Rent Per Day</label>
                        <input type="number" class="form-control" id="rent_per_day" name="rent_per_day" step="0.01" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Add Car</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>