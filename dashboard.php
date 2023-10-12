<?php

    include('config.php');
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

        <div style="margin-left: 250px; margin-bottom: 50px;">
            <h1 style="font-size: 65px; margin-top: 50px; color: #007bff; margin-left: 230px;">Railway Reservation System Admin Panel</h1>
            <h2 style="font-size: 40px; margin-top: 10px; margin-left: 350px;">Efficient Railway Management at Your Fingertips!</h2>
        </div>

        <div>
            <img src="assets/images/train.jpeg" alt="Image" style="width: 86.5%; height: 700px; margin-left: 250px;">
        </div>

        
        
    </div>
    
</body>

<script>

    var apiUrl = "<?php echo MY_API_URL; ?>";

</script>

<?php include 'common/footer.php';?>