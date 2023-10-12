<?php

    include('config.php');
    session_start();

    $userRole = $_SESSION['role'];

    // Check if a session variable is set
    if (!isset($_SESSION['id'])) {
        // Redirect to the login page
        header('Location: index.php');
        exit;
    }

?>

<?php include 'common/header.php';?>

<link href="assets/css/user.css" rel="stylesheet">

<title>Go Rail - User Management</title>

</head>

<body>

    <?php include 'common/sidebar.php';?>

    <div class="container-fluid">

        <div class= "container_header">
            <h1 style="background-color:#000; color:#fff; padding: 10px 15px; border-radius:10px;">User Management</h1>
        </div>

        <div class= "container_area">
            <button class="add-btn"><a href="add_new_user.php">Add Traveller</a></button>
        </div>

        <div class= "container_table_area">
            <table id="myTable" class="display">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>NIC</th>
                        <th>Phone No</th>
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
                    <h4 class="modal-title">View User Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- Modal body -->
                <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="modal-fname">First Name</label>
                        <input type="text" class="form-control" id="modal-fname">
                    </div>
                    <div class="form-group">
                        <label for="modal-lname">Last Name</label>
                        <input type="text" class="form-control" id="modal-lname">
                    </div>
                    <div class="form-group">
                        <label for="modal-email">Email</label>
                        <input type="text" class="form-control" id="modal-email">
                    </div>
                    <div class="form-group">
                        <label for="modal-nic">NIC</label>
                        <input type="text" class="form-control" id="modal-nic" disabled>
                    </div>
                    <div class="form-group">
                        <label for="modal-phone">Phone No</label>
                        <input type="number" class="form-control" id="modal-phone">
                    </div>
                    <div class="form-group">
                        <?php if ($userRole == 'back_office'): ?>
                            <label for="modal-status">Status</label>
                            <select class="form-control" id="modal-status">
                                <option value="active">Active</option>
                                <option value="inactive">Deactive</option>
                            </select>
                        <?php endif; ?>
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
        getAllUsers();
    });

    var table;

    function getAllUsers(){

        $.ajax({
            url: apiUrl + "User/",
            type: "GET",
            contentType: "application/json",
            success: function(response) {

                var existingTable = $('#myTable').DataTable();
                existingTable.clear().destroy();

                const filteredArray = response.filter(obj => obj.role === "traveller");

                var data = filteredArray;

                // Initialize DataTable
                table = $('#myTable').DataTable({
                    data: data,
                    columns: [
                        { 
                            data: 'fname'
                        },
                        {
                            data: 'lname'
                        },
                        {
                            data: 'email'
                        },
                        {
                            data: 'nic'
                        },
                        {
                            data: 'phone_no'
                        },
                        {
                            data: 'status'
                        },
                        {
                            data: null,
                            render: function (data, type, row) {
                                return '<button class="btn btn-primary view-button" data-toggle="modal" data-target="#myModal">View</button>';
                            },
                            orderable: false,
                            searchable: false
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
        $('#modal-fname').val(data.fname);
        $('#modal-lname').val(data.lname);
        $('#modal-email').val(data.email);
        $('#modal-nic').val(data.nic);
        $('#modal-phone').val(data.phone_no);
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

                var fname = $('#modal-fname').val();
                var lname = $('#modal-lname').val();
                var email = $('#modal-email').val();
                var nic = $('#modal-nic').val();
                var phone = $('#modal-phone').val();
                var status = $('#modal-status').val();

                // Create a JSON object with the input data
                var data = {
                    "fname": fname,
                    "lname": lname,
                    "nic": nic,
                    "phone_no": phone,
                    "email": email,
                    "status": status
                };

                $.ajax({

                    url: apiUrl + 'User/update',
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
                                getAllUsers();
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
                                    getAllUsers();
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