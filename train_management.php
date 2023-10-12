<?php

session_start();

?>

<?php include 'common/header.php';?>

<link href="assets/css/train.css" rel="stylesheet">

<title>Go Rail - Train Schedule Management</title>

</head>

<body>

    <?php include 'common/sidebar.php';?>

    <div class="container-fluid">

        <div class= "container_header">
            <h1 style="background-color:#000; color:#fff; padding: 10px 15px; border-radius:10px;">Train Schedule Management</h1>
        </div>

        <div class= "container_area">
            <button class="add-btn"><a href="add_new_schedule.php">Add Schedule</a></button>
        </div>

        <div class= "container_table_area">
            <table id="myTable" class="display">
                <thead>
                    <tr>
                        <th>Train Name</th>
                        <th>Date</th>
                        <th>Start Time(24H)</th>
                        <th>End Time(24H)</th>
                        <th>Description</th>
                        <th>No of Sheets</th>
                        <th>Ticket Price</th>
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
                    <h4 class="modal-title">View Schedule Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- Modal body -->
                <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="modal-trainname">Train Name</label>
                        <input type="text" class="form-control" id="modal-trainname" disabled>
                    </div>
                    <div class="form-group">
                        <label for="modal-date">Date</label>
                        <input type="date" class="form-control" id="modal-date" disabled>
                    </div>
                    <div class="form-group">
                        <label for="modal-starttime">Start Time</label>
                        <input type="time" class="form-control" id="modal-starttime" step="60">
                    </div>
                    <div class="form-group">
                        <label for="modal-endtime">End Time</label>
                        <input type="time" class="form-control" id="modal-endtime" step="60">
                    </div>
                    <div class="form-group">
                        <label for="modal-description">Description</label>
                        <input type="text" class="form-control" id="modal-description">
                    </div>
                    <div class="form-group">
                        <label for="modal-noofsheet">No of Sheets</label>
                        <input type="number" class="form-control" id="modal-noofsheet">
                    </div>
                    <div class="form-group">
                        <label for="modal-ticketprice">Ticket Price</label>
                        <input type="number" class="form-control" id="modal-ticketprice">
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

    $(document).ready( function () {
        $('#myTable').DataTable();
        getAllTrains();
    });

    function getAllTrains(){

        $.ajax({
            url: "https://localhost:7001/api/Train/",
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
                            data: 'trainname',
                            width: '15%'
                        },
                        {
                            data: 'date',
                            width: '10%'
                        },
                        {
                            data: 'starttime',
                            width: '10%'
                        },
                        {
                            data: 'endtime',
                            width: '10%'
                        },
                        {
                            data: 'description',
                            width: '20%'
                        },
                        {
                            data: 'noofsheet',
                            width: '10%'
                        },
                        {
                            data: 'ticketprice',
                            width: '10%'
                        },
                        {
                            data: 'status',
                            width: '5%'
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
        $('#modal-trainname').val(data.trainname);
        $('#modal-date').val(data.date);
        $('#modal-starttime').val(data.starttime);
        $('#modal-endtime').val(data.endtime);
        $('#modal-description').val(data.description);
        $('#modal-noofsheet').val(data.noofsheet);
        $('#modal-ticketprice').val(data.ticketprice);
        $('#modal-status').val(data.status);
    });

    $('#update_btn').click(function () {

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
                
                var trainname = $('#modal-trainname').val();
                var date = $('#modal-date').val();
                var starttime = $('#modal-starttime').val();
                var endtime = $('#modal-endtime').val();
                var description = $('#modal-description').val();
                var noofsheet = $('#modal-noofsheet').val();
                var ticketprice = $('#modal-ticketprice').val();
                var status = $('#modal-status').val();

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

                $.ajax({

                    url: 'https://localhost:7001/api/Train/update',
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
                                getAllTrains();
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
                                    getAllTrains();
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

    });

</script>

<?php include 'common/footer.php';?>