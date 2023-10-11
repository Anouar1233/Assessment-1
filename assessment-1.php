<!DOCTYPE html>
<html>

<head>
    <title>assessment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: black;
        }

        .name {
            border-collapse: collapse;
            width: 100%;
            color: #f9f9f9;
            font-family: monospace;
            font-size: 60px;
            text-align: center;
            height: 150px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            color: lightgrey;
            font-family: monospace;
            font-size: 25px;
            text-align: left;
            background-color: black;
        }

        th {
            text-align: center;
            background-color: black;
            color: lightgrey;
        }

        tr {
            background-color: black;
            text-align: center;
            box-sizing: content-box;
            width: 50px;
            height: 50px;
            padding: 10px;
            border: 4px solid black;
        }

        td {
            box-sizing: content-box;
            width: 50px;
            height: 50px;
            padding: 5px;
            border: 4px solid black;
        }

        .total {
            text-align: center;
            color: orange;
            text-decoration-color: black;
        }

        .color {
            color: black;
        }

        .modal-title {
            text-align: center;
            color: black;
        }
    </style>
</head>

<body>
    <div class="name">Employee List</div>
    <table>
        <tr>
            <th>Name</th>
            <th>Function</th>
            <th>Salary</th>
            <th>Remove</th>
            <th>Update</th>
        </tr>

        <?php
        // Function to connect to the database
        function connect_to_db()
        {
            $host = "localhost";
            $user = "anouar";
            $password = null;
            $database = "assessment";
            $conn = mysqli_connect($host, $user, $password, $database);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            return $conn;
        }

        // Function to add data to the database
        function add_employee_data()
        {
            $name = $_POST['name'];
            $function = $_POST['function'];
            $salary = $_POST['salary'];

            $conn = connect_to_db();

            $sql = "INSERT INTO people (name, function, salary) VALUES ('$name', '$function', '$salary')";

            if (mysqli_query($conn, $sql)) {
                echo "<meta http-equiv='refresh' content='0'>"; // Refresh the page to display the new employee information
                exit();
            } else {
                echo "ERROR: Could not execute $sql. " . mysqli_error($conn);
            }

            $conn->close();
        }

        // Function to update data in the database
        function update_employee_data()
        {
            $name = $_POST['update_name'];
            $function = $_POST['update_function'];
            $salary = $_POST['update_salary'];
            $employee_name = $_POST['employee_name'];

            $conn = connect_to_db();

            $sql = "UPDATE people SET name='$name', function='$function', salary='$salary' WHERE name='$employee_name'";

            if (mysqli_query($conn, $sql)) {
                header("Location: assessment-1.php");
                exit();
            } else {
                echo "ERROR: Could not execute $sql. " . mysqli_error($conn);
            }

            $conn->close();
        }

        // Function to remove data from the database
        function remove_employee_data()
        {
            $employee_name = $_POST['remove_employee_name'];
            $conn = connect_to_db();

            $sql = "DELETE FROM people WHERE name='$employee_name' LIMIT 1";

            if ($conn->query($sql) === TRUE) {
                header("Location: assessment-1.php");
                exit();
            } else {
                echo "Error: " . $conn->error;
            }

            $conn->close();
        }

        // Handle form input to add data to the database
        if (!empty($_POST['name']) && !empty($_POST['function']) && !empty($_POST['salary'])) {
            add_employee_data();
        }

        // Handle form input to update data in the database
        if (isset($_POST['employee_name'])) {
            update_employee_data();
        }

        // Handle form input to remove data from the database
        if (isset($_POST['remove_employee_name'])) {
            remove_employee_data();
        }

        // Query the database and display the data
        $conn = connect_to_db();
        $sql = "SELECT name, function, salary FROM people";
        $result = $conn->query($sql);

        if ($result === false) {
            echo "Error executing SQL query: " . mysqli_error($conn);
            exit();
        }

        $total_salary = 0;

        if ($result->num_rows > 0) {
            $index = 0;
            while ($row = $result->fetch_assoc()) {
                // Display the employee information
                echo "<tr><td>" . $row["name"] . "</td><td>" . $row["function"] . "</td><td>" . $row["salary"] . "</td>";
                echo "<td><a href='#myModal2_" . $index . "' class='btn btn-danger' data-bs-toggle='modal'>Remove</a></td>";
                echo "<td>";
                // Button to show update modal
                echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#update-modal-" . $index . "'>Update</button>";
                // Update modal
                echo "<div class='modal fade' id='update-modal-" . $index . "' tabindex='-1' role='dialog' aria-labelledby='update-modal-label-" . $index . "' aria-hidden='true'>";
                echo "<div class='modal-dialog' role='document'>";
                echo "<div class='modal-content'>";
                echo "<div class='modal-header'>";
                echo "<h5 class='modal-title' id='update-modal-label-" . $index . "'>Update Employee Data</h5>";
                echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
                echo "</div>";
                echo "<div class='color'>";
                echo "<form method='post' action='assessment-1.php'>";
                echo "<div class='modal-body'>";
                echo "<div class='mb-3'>";
                echo "<input type='hidden' name='employee_name' value='" . $row["name"] . "' />";
                echo "<label for='update_name' class='form-label'>Name</label>";
                echo "<input type='text' class='form-control' id='update_name' name='update_name' value='" . $row["name"] . "' autocomplete='off' required>";
                echo "</div>";
                echo "<div class='mb-3'>";
                echo "<label for='update_function' class='form-label'>Function</label>";
                echo "<input type='text' class='form-control' id='update_function' name='update_function' value='" . $row["function"] . "' autocomplete='off' required>";
                echo "</div>";
                echo "<div class='mb-3'>";
                echo "<label for='update_salary' class='form-label'>Salary</label>";
                echo "<input type='number' class='form-control' id='update_salary' name='update_salary' value='" . $row["salary"] . "' autocomplete='off' required>";
                echo "</div>";
                echo "</div>";
                echo "<div class='modal-footer'>";
                echo "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>";
                echo "<button type='submit' class='btn btn-primary'>Update</button>";
                echo "</div>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "</td>";
                // Remove modal
                echo "<div class='modal fade' id='myModal2_" . $index . "' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>";
                echo "<div class='modal-dialog' role='document'>";
                echo "<div class='modal-content'>";
                echo "<div class='modal-header'>";
                echo "<h5 class='modal-title' id='exampleModalLabel'> WARNING</h5>";
                echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
                echo "</div>";
                echo "<div class='modal-body'>";
                echo "<p>Are you sure you want to remove this employee?</p>";
                echo "</div>";
                echo "<div class='modal-footer'>";
                echo "<form method='post'>";
                echo "<input type='hidden' name='remove_employee_name' value='" . $row["name"] . "'>";
                echo "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>";
                echo "<button type='submit' class='btn btn-danger'>Remove</button>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                $total_salary += $row["salary"];
                $index++;
            }
            echo "<tr class='total'><td colspan='2'>Total Salary:</td><td>" . $total_salary . "</td><td></td><td></td></tr>";
        } else {
            echo "<tr><td colspan='5'>0 results</td></tr>";
        }

        // Close database connection
        $conn->close();
        ?>
    </table>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
        Add New
    </button>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="assessment-1.php">
                        <div class="mb-3">
                            <label for="name" class="form-label">Naam</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                        </div>
                        <div class="mb-3">
                            <label for="function" class="form-label">Function</label>
                            <input type="text" class="form-control" id="function" name="function" placeholder="Enter your function" pattern="[A-Za-z]+" required>
                        </div>
                        <div class="mb-3">
                            <label for="salary" class="form-label">Salary</label>
                            <input type="number" class="form-control" id="salary" name="salary" placeholder="Enter your Salary" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>