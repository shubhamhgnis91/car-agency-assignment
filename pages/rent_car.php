<?php
session_start();

// Check if the user is logged in and is a customer
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer') {
    header('Location: login.php');
    exit();
}

require 'dbconn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $car_id = $_POST['car_id'];
    $num_days = $_POST['days'];
    $start_date = $_POST['start_date'];
    $customer_id = $_SESSION['user_id'];

    // Fetch the rent per day for the selected car
    $stmt = $conn->prepare('SELECT model, vehicle_number, rent_per_day FROM cars WHERE id = ?');
    $stmt->bind_param('i', $car_id);
    $stmt->execute();
    $stmt->bind_result($model, $vehicle_number, $rent_per_day);
    $stmt->fetch();
    $stmt->close();

    // Calculate total rent
    $total_rent = $rent_per_day * $num_days;

    // Calculate end date
    $end_date = date('Y-m-d', strtotime($start_date . ' + ' . $num_days . ' days'));

    // Insert the booking details into the database
    $stmt = $conn->prepare('INSERT INTO bookings (car_id, customer_id, start_date, num_days, total_rent) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('iisid', $car_id, $customer_id, $start_date, $num_days, $total_rent);

    if ($stmt->execute()) {
        // Update the car's availability
        $update_stmt = $conn->prepare('UPDATE cars SET is_available = 0 WHERE id = ?');
        $update_stmt->bind_param('i', $car_id);
        $update_stmt->execute();
        $update_stmt->close();

        // Redirect to the bill page with booking details
        $_SESSION['bill'] = [
            'model' => $model,
            'vehicle_number' => $vehicle_number,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'num_days' => $num_days,
            'total_rent' => $total_rent
        ];

        header('Location: bill.php');
        exit();
    } else {
        $_SESSION['error'] = 'Failed to rent car. Please try again.';
    }

    $stmt->close();
    $conn->close();

    header('Location: available_cars.php');
    exit();
}
