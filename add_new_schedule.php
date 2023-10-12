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

<link href="assets/css/addSchedule.css" rel="stylesheet">

<title>Go Rail - Add new schedule</title>

</head>

<body>

    <?php include 'common/sidebar.php';?>

    <div class="container">

        <div class="form-register">
            <div class="text-center mb-4">
                <img class="mb-4 logo" src="assets/images/train-logo.png" alt="" width="100" height="100">
                <br>
                <h3>Add New Schedule</h3>
            </div>

            <hr class="mb-4">

            <?php if (isset($error)) { ?>
                <div class="alert alert-danger mb-4 " role="alert">
                    <?php echo $error; ?>
                </div>
            <?php } ?>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="trainname">Train Name</label>
                    <input type="text" class="form-control" id="trainname" name="trainname" required autofocus>
                </div>
                <div class="form-group col-md-6">
                    <label for="date">Date</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="starttime">Start Time</label>
                    <input type="time" class="form-control" id="starttime" name="starttime" step="60" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="endtime">End Time</label>
                    <input type="time" class="form-control" id="endtime" name="endtime" step="60" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="description">Description</label>
                    <input type="text" class="form-control" id="description" name="description" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="noofsheet">No of Sheets</label>
                    <input type="number" class="form-control" id="noofsheet" name="noofsheet" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="ticketprice">Ticket Price</label>
                    <input type="number" class="form-control" id="ticketprice" name="ticketprice" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="status">Status</label>
                    <select class="form-control" id="status">
                        <option value="active">Active</option>
                        <option value="inactive">Deactive</option>
                    </select>
                </div>
            </div>

            <br>

            <button id="add_btn" class="btn btn-lg btn-primary btn-block">Add new schedule</button>

            <br>

        </div>

    </div>

</body>

<script>

    $(document).ready(function() {
        
        //Handle button click
        $('#add_btn').click(function() {

            // Get the input values
            var trainname = $("#trainname").val();
            var date = $("#date").val();
            var starttime = $("#starttime").val();
            var endtime = $("#endtime").val();
            var description = $("#description").val();
            var noofsheet = $("#noofsheet").val();
            var ticketprice = $("#ticketprice").val();
            var status = $("#status").val();

            if( trainname == '' || date == '' || starttime == '' || endtime == '' || description == '' || noofsheet == '' || ticketprice == '' || status == ''){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Input Fields Cannot be empty!',
                });
            }else{

                // Create a JSON object with the input data
                var data = {
                    "trainname": trainname,
                    "date": date,
                    "starttime": starttime,
                    "endtime": endtime,
                    "description": description,
                    "noofsheet": noofsheet,
                    "ticketprice": ticketprice,
                    "status": status
                };

                // Make an AJAX POST request to your API or server endpoint
                $.ajax({
                    url: "https://localhost:7001/api/Train/addSchedule",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(data),
                    success: function(response) {
                        console.log(response);
                        // Display an success message to the user
                        Swal.fire({
                            icon: 'success',
                            title: 'Success...',
                            text: 'Train Schedule Added Successfully!.',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'train_management.php';
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        // Handle errors from the server
                        if (xhr.status === 400) {
                            // Display an error message to the user
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'For this Date, Train is already added!',
                            })
                        } else {
                            // Display an error message to the user
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                            });
                        }
                    }
                });

            }

        })

    })

</script>

<?php include 'common/footer.php';?>