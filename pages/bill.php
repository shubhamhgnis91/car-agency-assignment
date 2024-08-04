<?php
session_start();

// Check if the user is logged in and has a bill to display
if (!isset($_SESSION['user_id']) || !isset($_SESSION['bill'])) {
    header('Location: available_cars.php');
    exit();
}

$bill = $_SESSION['bill'];
unset($_SESSION['bill']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Bill</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Rental Bill</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Car Rental Details</h5>
                <p class="card-text"><strong>Vehicle Model:</strong> <?php echo htmlspecialchars($bill['model']); ?></p>
                <p class="card-text"><strong>Vehicle Number:</strong> <?php echo htmlspecialchars($bill['vehicle_number']); ?></p>
                <p class="card-text"><strong>Start Date:</strong> <?php echo htmlspecialchars($bill['start_date']); ?></p>
                <p class="card-text"><strong>End Date:</strong> <?php echo htmlspecialchars($bill['end_date']); ?></p>
                <p class="card-text"><strong>Number of Days:</strong> <?php echo htmlspecialchars($bill['num_days']); ?></p>
                <p class="card-text"><strong>Total Rent:</strong> ₹<?php echo htmlspecialchars($bill['total_rent']); ?></p>
                <a href="available_cars.php" class="btn btn-primary">Back to Available Cars</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>