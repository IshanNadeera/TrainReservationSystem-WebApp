<?php include 'common/header.php';?>

<link href="assets/css/addBooking.css" rel="stylesheet">

<title>Go Rail - Add new booking</title>

</head>

<body>

    <?php include 'common/sidebar.php';?>

    <div class="container">

        <div class="form-register">
            <div class="text-center mb-4">
                <img class="mb-4 logo" src="assets/images/train-logo.png" alt="" width="100" height="100">
                <br>
                <h3>Add New Booking</h3>
            </div>

            <hr class="mb-4">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="date">Reservation Date</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="trainname">Train Name</label>
                    <select class="form-control" id="trainname">
                        
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nic">Nic</label>
                    <input type="text" class="form-control" id="nic" name="nic" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nooftickets">No Of Tickets</label>
                    <input type="number" class="form-control" id="nooftickets" name="nooftickets" min="1" max="4" required>
                </div>
            </div>

            <br>

            <button id="add_btn" class="btn btn-lg btn-primary btn-block">Add new booking</button>

            <br>

        </div>

    </div>

</body>

<script>

    var trains;

    $(document).ready(function() {
        getAllTrains();
    })

    function getAllTrains(){

        $.ajax({
            url: 'https://localhost:7001/api/Train/',
            type: 'GET',
            contentType: "application/json",
            success: function (data) {
                // Handle the successful response
                trains = data;
            },
            error: function (xhr, status, error) {
                // Handle errors
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                });
            }
        });

    }

    // Get the date input element by its ID
    var dateInput = document.getElementById('date');

    // Add an event listener for the "change" event
    dateInput.addEventListener('change', function() {

        // To empty the selected value
        $('#trainname').empty();

        // Get the select element by its id
        var trainnameSelect = document.getElementById('trainname');

        // Get the selected date
        var selectedDate = dateInput.value;

        // Filter the train objects with the selected date
        const filteredTrains = trains.filter(trainObj => trainObj.date === selectedDate);

        // Iterate over the filtered trains and create options
        filteredTrains.forEach(trainObj => {
            const option = document.createElement('option');
            option.value = trainObj.trainname;
            option.text = trainObj.trainname;
            trainnameSelect.appendChild(option);
        });

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

    //Handle button click
    $('#add_btn').click(function() {

        // Get the input values
        var trainname = $("#trainname").val();
        var date = $("#date").val();
        var nic = $("#nic").val();
        var name = $("#name").val();
        var nooftickets = $("#nooftickets").val();
        var bookingdate = getCurrentDate();

        if( trainname == '' || date == '' || nic == '' || name == '' || nooftickets == ''){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Input Fields Cannot be empty!',
            });
        }else{

            // Create a JSON object with the input data
            var data = {
                "trainname": trainname,
                "reservationdate": date,
                "nic": nic,
                "bookingdate": bookingdate,
                "name": name,
                "nooftickets": nooftickets,
                "status": "active"
            };

            // Make an AJAX POST request to your API or server endpoint
            $.ajax({
                url: "https://localhost:7001/api/Booking/addBooking",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify(data),
                success: function(response) {
                    console.log(response);
                    // Display an success message to the user
                    Swal.fire({
                        icon: 'success',
                        title: 'Success...',
                        text: 'Booking Added Successfully!.',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'booking_management.php';
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
                            text: 'For this NIC, Train is already booked!',
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

</script>

<?php include 'common/footer.php';?>