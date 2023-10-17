<?php

// session_start();

$fname = $_SESSION['fname'];
$lname = $_SESSION['lname'];
$userRole = $_SESSION['role'];

?>

<div class="sidebar">

    <header>
        <img src="./assets/images/train-logo.png" alt="Go Rail Logo" width="40" height="40" style="margin-left:-20px">
        <b>Go Rail</b>
    </header>

    <div class="card" style="padding: 20px; text-align: center; background: #dddddd;">
        <div style="display: inline-block; margin-top:10px;">
            <img src="./assets/images/profile.png" alt="Go Rail Logo" width="100" height="100">
        </div>
        <div style="display: inline-block; margin-top: 20px;">
            <h4><?php echo $fname .' '. $lname ?></h4>
            <p><?php echo $userRole ?></p>
        </div>
    </div>

    <ul>
        <li><a href="dashboard.php"><i class="fas fa-qrcode" style="margin-right: 15px;"></i>Dashboard</a></li>
        <li><a href="user_management.php"><i class="fas fa-user" style="margin-right: 15px;"></i>User Management</a></li>
        <?php if ($userRole == 'back_office'): ?>
            <li><a href="train_management.php"><i class="fas fa-stream" style="margin-right: 15px;"></i>Train Management</a></li>
        <?php endif; ?>
        <li><a href="booking_management.php"><i class="fa fa-book" style="margin-right: 15px;"></i>Booking Management</a></li>
        <li><a href="logout.php"><i class="fa fa-reply" style="margin-right: 15px;"></i>Logout</a></li>
    </ul>

</div>