<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Connect to the database
$connection = mysqli_connect("localhost", "root", "", "attendance");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

// Retrieve archived data
$query = "SELECT * FROM archive_table_walk_in ORDER BY date DESC";
$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Archived Logs Page</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+1p:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="fontawesome-free-6.5.1-web\css\all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>


    <style>
        * {
            font-family: 'M PLUS 1p', sans-serif;
        }

        .divider {
            margin: 0 auto;
            width: 95%;
            border-bottom: 1px solid grey;
            padding-top: 5px;
        }

        .logs-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: black;
        }

        .attendance-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            padding-left: 30px;
            margin-bottom: 20px;
            width: calc(100% - 30px);
        }

        .logs-name,
        .logs-time {
            color: black;
        }

        .content {
            overflow: auto;
        }

        .logs-btn {
            margin-right: 2rem;
        }

        button {
            border-radius: 10px;
        }

        table {
            text-align: center;
        }

        .calendar-filter {
          padding-left: 550px;
          padding-top: 10px;
        }

         /* Style for Flatpickr calendar date */
        .flatpickr-day {
            color: black; /* Change the color of the date */
        }

        /* Style for Flatpickr calendar year and month */
        .flatpickr-current-month,
        .flatpickr-monthDropdown-months,
        .numInputWrapper{
            color: black; /* Change the color of the month and year */
        }


        /* Style for Flatpickr dropdown options */
        .flatpickr-monthDropdown-months .flatpickr-monthDropdown-month,
        .flatpickr-yearDropdown-years .flatpickr-yearDropdown-year {
            color: black; /* Change the color of the dropdown option */
        }
       

    </style>
</head>
<body>
    <!-- header -->
    <div class="dashboard-header">
        <div class="title">
            <h3>Altitude Gym Management</h3>
        </div>
        <div class="logout">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Administrator
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <!-- end of header -->

    <!-- body -->

    <div class="body-container">
        <!-- calendar filter -->
        <div class="calendar-filter">
            <input id="calendar" type="text" placeholder="Select Date">
        </div>
        <!-- end of calendar filter -->

        <!-- sidenav -->
        <div class="sidenav">
            <a href="dashboard.php"><i class="fa fa-home" aria-hidden="true"></i>Home</a>
            <a href="members.php"><i class="fa fa-users"></i>Members</a>
            <a href="dues.php"><i class="fa fa-credit-card"></i>Dues</a>
            <a href="trainers.php"><i class="fa fa-user"></i>Trainers</a>
            <a href="walk-in.php"><i class='fa-solid fa-person-walking'></i>Walk-in</a>
            <a href="logs.php"><i class="fa-solid fa-right-to-bracket"></i>Logs</a>
        </div>
        <!-- end of sidenav -->

        <!-- main content -->
        <div class="content">
            <div class="logs-content">
                <h3>Archived Logs Walk-in</h3>
                <div class="logs-btn">
                    <button onclick="printToPDF()" target="_blank">Print to PDF</button>
                </div>
            </div>
            <div class="divider"></div>

            <?php
            date_default_timezone_set('Asia/Macao');


            if ($result && mysqli_num_rows($result) > 0) {
                echo '<div id="members-container" class="attendance-table">';
                echo '<div class="container mt-4">';
                echo '    <table id="members-table" class="table">';
                echo '        <thead>';
                echo '            <tr>';
                echo '                <th>Name</th>';
                echo '                <th>Time-in</th>';
                echo '                <th>Time-out</th>';
                echo '                <th>Date</th>';
                echo '            </tr>';
                echo '        </thead>';
                echo '        <tbody>';

                // Loop through each row in the result set
                while ($row = mysqli_fetch_assoc($result)) {
                    echo ' <tr>';
                    echo '     <td>' . $row['name'] . '</td>';
                    echo '     <td>' . date("h:i A", strtotime($row['time_in'])) . '</td>';
                    echo '     <td>' . ($row['time_out'] ? date("h:i A", strtotime($row['time_out'])) : 'N/A') . '</td>';
                    echo '     <td>' . date("m-d-Y", strtotime($row['date'])) . '</td>';
                    echo ' </tr>';
                }

                echo '        </tbody>';
                echo '    </table>';
                echo '</div>';
                echo '</div>';
            } else {
                echo "<p>No members found.</p>";
            }

            // Close the database connection
            mysqli_close($connection);
            ?>

            <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
            <script>
                flatpickr("#calendar", {
                dateFormat: "Y-m-d",
                onClose: function(selectedDates, dateStr, instance) {
                    // Convert selected date to MySQL date format (Y-m-d)
                    const selectedDate = moment(selectedDates[0]).format('YYYY-MM-DD');
                    
                    // Send AJAX request to fetch members for the selected date
                    const xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            // Update table with fetched members
                            document.getElementById("members-table").getElementsByTagName("tbody")[0].innerHTML = this.responseText;
                        }
                    };
                    xhttp.open("GET", "fetch_walkin.php?date=" + selectedDate, true);
                    xhttp.send();
                }
            });


                function printToPDF() {
                    const selectedDate = document.getElementById("calendar").value;
                    // If no date is selected, generate PDF of all archived data
                if (selectedDate === '') {
                    window.open('generate_pdf_archived_walk-in.php', '_blank');
                }
                else {
                    // Check if the table has been filtered
                    const filteredData = document.getElementById("members-table").innerHTML.trim();
                    
                    // If the table has filtered data, generate PDF from filtered data
                    if (filteredData !== '') {
                        window.open('generate_pdf_walkin_filtered.php?date=' + selectedDate, '_blank');
                    } else {
                        // If no filter applied, generate PDF from the currently displayed data
                        window.open('generate_pdf_archived_walk-in.php', '_blank');
                    }
                }
            }
            </script>
        </div>
    </div>
    </div>
    </div>
</body>
</html>
