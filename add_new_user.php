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

<link href="assets/css/register.css" rel="stylesheet">

<title>Go Rail - Add new user</title>

</head>

<body>

    <?php include 'common/sidebar.php';?>

    <div class="container" style="margin-left: 500px;">

        <div class="form-register">
            <div class="text-center mb-4">
                <img class="mb-4 logo" src="assets/images/train-logo.png" alt="" width="100" height="100">
                <br>
                <h3>Register New User</h3>
            </div>

            <br>
            <hr class="mb-4">
            <br>

            <?php if (isset($error)) { ?>
                <div class="alert alert-danger mb-4 " role="alert">
                    <?php echo $error; ?>
                </div>
            <?php } ?>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="fname">First Name</label>
                    <input type="text" class="form-control" id="fname" name="fname" required autofocus>
                </div>
                <div class="form-group col-md-6">
                    <label for="lname">Last Name</label>
                    <input type="text" class="form-control" id="lname" name="lname" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nic">NIC</label>
                    <input type="text" class="form-control" id="nic" name="nic" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="contactno">Contact No</label>
                    <input type="number" class="form-control" id="contactno" name="contactno" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="form-row mb-4">
                <div class="form-group col-md-6">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="input-group-append">
                            <span class="input-group-text toggle-password">
                                <i class="far fa-eye"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="cpassword">Confirm Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="cpassword" name="cpassword" required>
                        <div class="input-group-append">
                            <span class="input-group-text toggle-password">
                                <i class="far fa-eye"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <br>

            <button id="register_btn" class="btn btn-lg btn-primary btn-block">Add new user</button>

            <br>

        </div>

    </div>

</body>

<script>

    var apiUrl = "<?php echo MY_API_URL; ?>";

    document.addEventListener('DOMContentLoaded', function () {
        const togglePasswordIcons = document.querySelectorAll('.toggle-password');

        togglePasswordIcons.forEach(icon => {
            icon.addEventListener('click', function () {
                const inputField = this.closest('.input-group').querySelector('input');
                if (inputField.type === 'password') {
                    inputField.type = 'text';
                    icon.innerHTML = '<i class="far fa-eye-slash"></i>';
                } else {
                    inputField.type = 'password';
                    icon.innerHTML = '<i class="far fa-eye"></i>';
                }
            });
        });
    });

    $(document).ready(function() {
        
        //Handle button click
        $('#register_btn').click(function() {

            // Get the input values
            var fname = $("#fname").val();
            var lname = $("#lname").val();
            var nic = $("#nic").val();
            var contactno = $("#contactno").val();
            var email = $("#email").val();
            var password = $("#password").val();
            var cpassword = $("#cpassword").val();

            if( fname == '' || lname == '' || nic == '' || contactno == '' || email == '' || password == '' || cpassword == ''){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Input Fields Cannot be empty!',
                });
            }else if(password != cpassword){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Passwords not matched!',
                });
            }else{

                // Create a JSON object with the input data
                var data = {
                    "fname": fname,
                    "lname": lname,
                    "nic": nic,
                    "phone_no": contactno,
                    "email": email,
                    "password": password,
                    "status": 'active',
                    "role": 'traveller'
                };

                // Make an AJAX POST request to your API or server endpoint
                $.ajax({
                    url: apiUrl + "User/register",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(data),
                    success: function(response) {
                        console.log(response);
                        // Display an success message to the user
                        Swal.fire({
                            icon: 'success',
                            title: 'Success...',
                            text: 'Registered Successfully!.',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'user_management.php';
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
                                text: 'NIC is already registered!',
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