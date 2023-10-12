<?php

session_start();

// Check if a session variable is set
if (!isset($_SESSION['id'])) {
    // Redirect to the login page
    header('Location: index.php');
    exit;
}

?>

<?php include 'common/header.php';?>

<link href="assets/css/booking.css" rel="stylesheet">

<title>Go Rail - Dashboard</title>

</head>

<body>

    <?php include 'common/sidebar.php';?>

    <div class="container-fluid">

        <div class= "container_header">
            <h1 style="background-color:#000; color:#fff; padding: 10px 15px; border-radius:10px;">Dashboard</h1>
        </div>

        <!-- <div class= "container_area">
            <button class="add-btn"><a href="add_new_booking.php">Add Booking</a></button>
        </div> -->

        <div>
            <img src="assets/images/train.jpeg" alt="Image" style="width: 86%; height: 600px; margin-left: 250px;">
        </div>

        <div style="margin-left: 250px;">
            <h1 style="font-size: 65px; margin-top: 50px; color: #007bff; margin-left: 220px;">Railway Reservation System Admin Panel</h1>
            <h2 style="font-size: 40px; margin-top: 10px; margin-left: 330px;">Efficient Railway Management at Your Fingertips!</h2>
        </div>
        
    </div>
    
</body>

<script>

</script>

<?php include 'common/footer.php';?>