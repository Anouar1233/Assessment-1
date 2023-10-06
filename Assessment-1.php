<!DOCTYPE html>
<html>

<head>
    <title>assessment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
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
        .color{color: black;
        }


        .modal-title{
            text-align: center;
            color: black;}    
        
        </style>
</head>

<body>
    <div class="name">Employee List</div>
    <table>
        <tr>
            <th>Name</th>
            <th>ID</th>
            <th>Function</th>
            <th>Salary</th>
            <th>Remove</th>
            <th>Update</th>
        </tr>

        <?php
        $host = "localhost";
        $user = "anouar";
        $password = null;
        $database = "assessment";
        $conn = mysqli_connect($host, $user, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // input to add data to the database
        if (!empty($_POST['name']) && !empty($_POST['function']) && !empty($_POST['salary'])) {
            $name = $_POST['name'];
            $function = $_POST['function'];
            $salary = $_POST['salary'];
            $unique_id = generate_unique_id(); // Generate a unique ID for the employee

            if(isset($_POST['employee_id'])) {
                // Updating an existing employee
                $employee_id = $_POST['employee_id'];
                $sql = "UPDATE people SET name='$name', function='$function', salary='$salary' WHERE unique_id='$employee_id'";
                if (mysqli_query($conn, $sql)) {
                    header("Location: assessment-1.php");
                    exit();
                } else {
                    echo "ERROR: Could not execute $sql. " . mysqli_error($conn);
                }
            } else {
                // Adding a new employee
                $sql = "INSERT INTO people (name, unique_id, function, salary) VALUES ('$name', '$unique_id', '$function', '$salary')";
                if (mysqli_query($conn, $sql)) {
                    echo "<meta http-equiv='refresh' content='0'>"; // Refresh the page to display the new employee information
                    exit();
                } else {
                    echo "ERROR: Could not execute $sql. " . mysqli_error($conn);
                }
            }
        }

        // Handle form input to remove data from the database
        if (isset($_POST['remove_unique_id'])) {
            $unique_id = $_POST['remove_unique_id'];
            $sql = "DELETE FROM people WHERE unique_id='$unique_id' LIMIT 1";
            if ($conn->query($sql) === TRUE) {
                // Remove the ID from the local storage
                echo "<script>localStorage.removeItem('employee_id_".$unique_id."');</script>";
                header("Location: assessment-1.php");
                exit();
            } else {
                echo "Error: " . $conn->error;
            }
        }

        // Query the database and display the data
        $sql = "SELECT name, unique_id, function, salary FROM people"; 
        $result = $conn->query($sql);

        if ($result === false) {
            echo "Error executing SQL query: " . mysqli_error($conn);
            exit();
        }

        $total_salary = 0;

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Store the ID in the local storage if it doesn't exist already
                echo "<script>if (!localStorage.getItem('employee_id_".$row['unique_id']."')) { localStorage.setItem('employee_id_".$row['unique_id']."', '".$row['unique_id']."'); }</script>";

                // Display the generated ID or the stored ID
                $display_id = $row["unique_id"];
                echo "<tr><td>" . $row["name"] . "</td><td>" . $row["unique_id"] . "</td><td>" . $row["function"] . "</td><td>" . $row["salary"] . "</td>";
                echo "<td><a href='#myModal2_" . $row["unique_id"] . "' class='btn btn-danger' data-bs-toggle='modal'>Remove</a></td>";
                echo "<td>";
                // Button to show update modal
                echo "<button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#myModal_" . $row["unique_id"] . "'>Update</button>";
                // Update modal
                echo "<div class='modal fade' id='myModal_" . $row["unique_id"] . "' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel_" . $row["unique_id"] . "' aria-hidden='true'>";
                echo "<div class='modal-dialog' role='document'>";
                echo "<div class='modal-content'>";
                echo "<div class='modal-header'>";
                echo "<h5 class='modal-title' id='exampleModalLabel_" . $row["unique_id"] . "'>Update Employee Data</h5>";
                echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
                echo "</div>";
                echo "<div class='color'";
                echo "<form method='post' action='assessment-1.php'>";
                echo "<div class='modal-body'>";
                echo "<div class='mb-3'>";
                echo "<input type='hidden' name='employee_id' value='" . $row["unique_id"] . "' />";
                echo "<label for='name' class='form-label'>Name</label>";
                echo "<input type='text' class='form-control' id='name' name='name' value='" . $row["name"] . "' required>";
                echo "</div>";
                echo "<div class='mb-3'>";
                echo "<label for='function' class='form-label'>Function</label>";
                echo "<input type='text' class='form-control' id='function' name='function' value='" . $row["function"] . "' required>";
                echo "</div>";
                echo "<div class='mb-3'>";
                echo "<label for='salary' class='form-label'>Salary</label>";
                echo "<input type='number' class='form-control' id='salary' name='salary' value='" . $row["salary"] . "' required>";
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
                echo "<div class='modal fade' id='myModal2_" . $row["unique_id"] . "' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>";
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
                echo "<input type='hidden' name='remove_unique_id' value='" . $row["unique_id"] . "'>";
                echo "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>";
                echo "<button type='submit' class='btn btn-danger'>Remove</button>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                $total_salary += $row["salary"];
            }
            echo "<tr class='total'><td colspan='3'>Total Salary:</td><td>" . $total_salary . "</td><td></td><td></td></tr>";
        } else {
            echo "<tr><td colspan='6'>0 results</td></tr>";
        }

        $conn->close();

        // Function to generate a unique ID for each employee
        function generate_unique_id() {
            $id = mt_rand(100000, 999999);

            // Check if the generated ID already exists in the database
            $host = "localhost";
            $user = "anouar";
            $password = null;
            $database = "assessment";
            $conn = mysqli_connect($host, $user, $password, $database);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT unique_id FROM people WHERE unique_id='$id'";
            $result = $conn->query($sql);

            while ($result->num_rows > 0) {
                // Keep generating IDs until a unique one is found
                $id = mt_rand(100000, 999999);
                $sql = "SELECT unique_id FROM people WHERE unique_id='$id'";
                $result = $conn->query($sql);
            }

            $conn->close();

            return $id;
        }
        ?>

    </table>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
       Add New
    </button>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Toevoegen?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="assessment-1.php">
                        <div class="mb-3">
                            <label for="name" class="form-label">Naam</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name"required>
                        </div>
                        <div class="mb-3">
                            <label for="function" class="form-label">Function</label>
                            <input type="text" class="form-control" id="function" name="function" placeholder="Enter your function"pattern="[A-Za-z]+"required>
                        </div>
                        <div class="mb-3">
                            <label for="salary" class="form-label">Salary</label>
                            <input type="number" class="form-control" id="salary" name="salary" placeholder="Enter your Salary"required>
                        </div>
                        <button type="submit" class="btn btn-primary">Toevoegen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>