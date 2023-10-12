<?php include 'common/header.php';?>

<link href="assets/css/login.css" rel="stylesheet">

<title>Go Rail - Login</title>

<style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
</style>

</head>

<body>

    <div class="container">

        <div class="form-signin">
            <div class="text-center mb-4">
                <img class="mb-4 logo" src="assets/images/train-logo.png" alt="" width="100" height="100">
                <br>
                <h3>Log In</h3>
            </div>

            <hr class="mb-4">
            
            <div class="form-label-group">
                <input type="text" name="nic" id="inputNic" class="form-control" placeholder="NIC" required autofocus>
                <label for="inputNic">Nic</label>
            </div>

            <br>

            <div class="form-label-group">
                <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
                <label for="inputPassword">Password</label>
            </div>

            <br>

            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" value="remember-me"> Remember me
                </label>
            </div>

            <button class="btn btn-lg btn-primary btn-block" id="login_btn">Log in</button>
            <br>
            <p class="text-center">Don't have an account? <a href="register.php"><span class="spanTxt">Register</span></a></p>
            <p class="mt-5 mb-3 text-muted text-center">All rights reserved &copy; 2023</p>
        </div>
        
    </div>

</body>

<script>

    $(document).ready(function() {

        //Handle button click
        $('#login_btn').click(function() {

            // Get the input values
            var nic = $("#inputNic").val();
            var password = $("#inputPassword").val();

            if(nic == ''){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'NIC Cannot be empty!',
                })
            }else if(password == ''){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Password Cannot be empty!',
                })
            }else{
                // Create a JSON object with the input data
                var data = {
                    "nic": nic,
                    "password": password
                };

                // Make an AJAX POST request to your API or server endpoint
                $.ajax({
                    url: "https://localhost:7001/api/User/login",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(data),
                    success: function(response) {

                        if(response.role == 'traveller'){
                            // Display an error message to the user
                            Swal.fire({
                                icon: 'error',
                                title: 'Error...',
                                text: 'Not a valid user!',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'index.php';
                                };
                            });
                        }else if(response.status != 'active'){
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Account deactivated. Please contact admin!',
                            })
                        }else if(response.role == 'travel_agent'){
                            saveResponseToSession(response,0);
                        }else{
                            saveResponseToSession(response,1);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle errors from the server
                        if (xhr.status === 404) {
                            // Display an error message to the user
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'User not found. Please check your credentials!',
                            })
                        }else if(xhr.status === 401){
                            // Display an error message to the user
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Password is not correct. Please check your credentials!',
                            })
                        } else {
                            // Display an error message to the user
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                            })
                        }
                    }
                });
            }
        });

        function saveResponseToSession(response, role) {

            var responseData = JSON.stringify(response);

            // Send the response data to a PHP script
            $.ajax({
                url: 'save_to_session.php',
                type: 'POST',
                data: responseData,
                success: function() {
                    // Redirect to the new page
                    if(role == 0){
                        window.location.href = 'dashboard.php';
                    }else{
                        window.location.href = 'dashboard.php';
                    }
                    
                },
                error: function() {
                    // Display an error message to the user
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Something went wrong!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'index.php';
                        };
                    });
                    
                }
            });
        }

    });

</script>

<?php include 'common/footer.php';?>