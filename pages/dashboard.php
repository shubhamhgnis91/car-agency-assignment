<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require 'dbconn.php';

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

// Fetch user details
$query = "SELECT username, email FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Dashboard</a>
        <a class="navbar-brand" href="..\index.php">Home</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="jumbotron">
            <h1 class="display-4">Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
            <p class="lead">Email: <?php echo htmlspecialchars($user['email']); ?></p>
            <hr class="my-4">
            <?php if ($user_type === 'agency'): ?>
                <h2>Agency Options</h2>
                <a class="btn btn-primary btn-lg" href="view_booked_cars.php" role="button">View Booked Cars</a>
                <a class="btn btn-primary btn-lg" href="add_car.php" role="button">Add Car</a>
            <?php else: ?>
                <h2>User Options</h2>
                <a class="btn btn-primary btn-lg" href="available_cars.php" role="button">View Available Cars</a>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>