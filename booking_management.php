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

<title>Go Rail - Booking Management</title>

</head>

<body>

    <?php include 'common/sidebar.php';?>

    <div class="container-fluid">

        <div class= "container_header">
            <h1 style="background-color:#000; color:#fff; padding: 10px 15px; border-radius:10px;">Train Booking Management</h1>
        </div>

        <div class= "container_area">
            <button class="add-btn"><a href="add_new_booking.php">Add Booking</a></button>
        </div>

        <div class= "container_table_area">
            <table id="myTable" class="display">
                <thead>
                    <tr>
                        <th>NIC</th>
                        <th>Train Name</th>
                        <th>Booking Date</th>
                        <th>Name</th>
                        <th>No of Tickets</th>
                        <th>Reservation Date</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>

        <div class="modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">View Booking Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- Modal body -->
                <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="modal-nic">NIC</label>
                        <input type="text" class="form-control" id="modal-nic" disabled>
                    </div>
                    <div class="form-group">
                        <label for="modal-trainname">Train Name</label>
                        <input type="text" class="form-control" id="modal-trainname" disabled>
                    </div>
                    <div class="form-group">
                        <label for="modal-bookingdate">Booking Date</label>
                        <input type="date" class="form-control" id="modal-bookingdate" disabled>
                    </div>
                    <div class="form-group">
                        <label for="modal-name">Name</label>
                        <input type="text" class="form-control" id="modal-name">
                    </div>
                    <div class="form-group">
                        <label for="modal-nooftickets">No of Tickets</label>
                        <input type="number" class="form-control" id="modal-nooftickets" min="1" max="4">
                    </div>
                    <div class="form-group">
                        <label for="modal-reservationdate">Reservation Date</label>
                        <input type="date" class="form-control" id="modal-reservationdate" disabled>
                    </div>
                    <div class="form-group">
                        <label for="modal-status">Status</label>
                        <select class="form-control" id="modal-status">
                            <option value="active">Active</option>
                            <option value="inactive">Deactive</option>
                        </select>
                    </div>
                
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="update_btn" class="btn btn-primary" data-dismiss="modal">Save Changes</button>
                </div>
                
                </div>
            </div>
        </div>
        
    </div>
    
</body>

<script>

    var apiUrl = "<?php echo MY_API_URL; ?>";

    $(document).ready( function () {
        $('#myTable').DataTable();
        getAllBookings();
    });

    function getAllBookings(){

        $.ajax({
            url: apiUrl + "Booking/",
            type: "GET",
            contentType: "application/json",
            success: function(response) {

                var existingTable = $('#myTable').DataTable();
                existingTable.clear().destroy();

                var data = response;

                // Initialize DataTable
                table = $('#myTable').DataTable({
                    data: data,
                    columns: [
                        { 
                            data: 'nic',
                            width: '10%'
                        },
                        {
                            data: 'trainname',
                            width: '15%'
                        },
                        {
                            data: 'bookingdate',
                            width: '10%'
                        },
                        {
                            data: 'name',
                            width: '15%'
                        },
                        {
                            data: 'nooftickets',
                            width: '10%'
                        },
                        {
                            data: 'reservationdate',
                            width: '15%'
                        },
                        {
                            data: 'status',
                            width: '10%'
                        },
                        {
                            data: null,
                            render: function (data, type, row) {
                                return '<button class="btn btn-primary view-button" data-toggle="modal" data-target="#myModal">View</button>';
                            },
                            orderable: false,
                            searchable: false,
                            width: '10%'
                        }
                    ]
                });
                
            },
            error: function(xhr, status, error) {
                // Handle errors from the server
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                });
            }
        });
    }

    // Add an event listener to populate modal content when the "View" button is clicked.
    $('#myTable').on('click', '.view-button', function () {
        var data = table.row($(this).parents('tr')).data();
        $('#modal-nic').val(data.nic);
        $('#modal-trainname').val(data.trainname);
        $('#modal-bookingdate').val(data.bookingdate);
        $('#modal-name').val(data.name);
        $('#modal-nooftickets').val(data.nooftickets);
        $('#modal-reservationdate').val(data.reservationdate);
        $('#modal-status').val(data.status);
    });

    // Add an event listener to populate modal content when the "QR View" button is clicked.
    $('#myTable').on('click', '.qr-view-button', function () {
        var data = table.row($(this).parents('tr')).data();
        console.log(data.status)
    });

    $('#update_btn').click(function () {

        var nic = $('#modal-nic').val();
        var trainname = $('#modal-trainname').val();
        var bookingdate = $('#modal-bookingdate').val();
        var name = $('#modal-name').val();
        var nooftickets = $('#modal-nooftickets').val();
        var reservationdate = $('#modal-reservationdate').val();
        var status = $('#modal-status').val();
        var currentdate = getCurrentDate();

        var check = checkUpdate(reservationdate,currentdate);

        if(check == false){
            // Display an error message to the user
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "Can't update reservation!",
            });
        }else{
            // Display an popup message to ask to update
            Swal.fire({
                icon: 'warning',
                title: 'Warning...',
                text: 'Do you want to update data ?',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    // Create a JSON object with the input data
                    var data = {
                        "nic": nic,
                        "trainname": trainname,
                        "bookingdate": bookingdate,
                        "name": name,
                        "nooftickets": nooftickets,
                        "reservationdate": reservationdate,
                        "status": status
                    };

                    $.ajax({

                        url: apiUrl + 'Booking/update',
                        type: 'PUT',
                        contentType: 'application/json',
                        dataType: 'json',
                        data: JSON.stringify(data),
                        success: function (response) {
                            // Display an success message to the user
                            Swal.fire({
                                icon: 'success',
                                title: 'Success...',
                                text: 'Updated Successfully!.',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    getAllBookings();
                                }
                            });
                        },
                        error: function (xhr, status, error) {
                            // Handle errors here
                            if (xhr.status === 200) {
                                // Display an success message to the user
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success...',
                                    text: 'Updated Successfully!.',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        getAllBookings();
                                    }
                                });
                            }else {
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
            });
        }

    });

    function getCurrentDate(){

        const currentDate = new Date();

        // Extract year, month, and day
        const year = currentDate.getFullYear();
        const month = String(currentDate.getMonth() + 1).padStart(2, '0');
        const day = String(currentDate.getDate()).padStart(2, '0');

        // Format the date as "YYYY-MM-DD"
        const formattedDate = `${year}-${month}-${day}`;

        return formattedDate;
    }

    function checkUpdate(reservationdate,currentdate){
        
        // Define the current date and reservation date as Date objects
        const currentDate = new Date(currentdate);
        const reservationDate = new Date(reservationdate);

        // Calculate the difference in milliseconds between the two dates
        const timeDifference = reservationDate - currentDate;

        // Calculate the difference in days
        const daysDifference = timeDifference / (1000 * 60 * 60 * 24);

        if (daysDifference >= 5) {
            //The current date is at least 5 days before the reservation date
            return true;
        } else {
            //The current date is less than 5 days before the reservation date
            return false;
        }

    }

</script>

<?php include 'common/footer.php';?>