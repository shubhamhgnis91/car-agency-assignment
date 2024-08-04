<?php
session_start();

// Check if the user is logged in and is an agency
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'agency') {
    header('Location: login.php');
    exit();
}

require 'dbconn.php';

$agency_id = $_SESSION['user_id'];

// Fetch the list of booked cars added by the logged-in agency
$query = "
    SELECT 
        b.id AS booking_id, 
        c.model AS car_model, 
        c.vehicle_number, 
        u.username AS customer_name, 
        u.email AS customer_email, 
        b.start_date, 
        b.num_days, 
        b.total_rent 
    FROM bookings b
    JOIN cars c ON b.car_id = c.id
    JOIN users u ON b.customer_id = u.id
    WHERE c.agency_id = ?
    ORDER BY b.start_date DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $agency_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Booked Cars</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Booked Cars</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Car Model</th>
                    <th>Vehicle Number</th>
                    <th>Customer Name</th>
                    <th>Customer Email</th>
                    <th>Start Date</th>
                    <th>Number of Days</th>
                    <th>Total Rent</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['booking_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['car_model']); ?></td>
                        <td><?php echo htmlspecialchars($row['vehicle_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['customer_email']); ?></td>
                        <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['num_days']); ?></td>
                        <td>₹<?php echo htmlspecialchars($row['total_rent']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>