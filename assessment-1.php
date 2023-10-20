<!DOCTYPE html>
<html>

<head>
  <title>Employee List</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body {
      background-color: white;
      padding: 20px;
    }

    .card {
      border: none;
      box-shadow: 0px 0px 20px 0px rgba(0, 0, 0, 0.2);
      width: 1255px;
      margin: auto;
      padding: 20px;
    }

    .name {
      color: black;
      font-size: 60px;
      text-align: center;
      margin-bottom: 40px;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      color: white;
      font-family: monospace;
      font-size: 25px;
      text-align: left;
      background-color: white;
    }

    th {
      text-align: center;
      background-color: white;
      color: white;
    }

    tr {
      background-color: white;
      text-align: center;
      box-sizing: content-box;
      width: 50px;
      height: 50px;
      padding: 10px;

    }

    td {
      box-sizing: content-box;
      width: 50px;
      height: 50px;
      padding: 5px;
      
    }

    .total {
      text-align: center;
      color: orange;
      text-decoration-color: white;
    }

    .color {
      color: white;
    }

    .modal-title {
      text-align: center;
      color: black;
    }

    .card-header {
      background-color: white;
      color: black;
    }

    .card-body {
      padding: px;
    }

    .button-container {
      display: flex;
      justify-content: center;
      margin-top: 50px;
    }

    .btn {
      margin-right: 10px;
      margin-left: 10px;
    }
  </style>
  <link rel="stylesheet" href="custom.css">
</head>

<body>


  <script>
    // Set the time limit to 2 minutes (120000 milliseconds)
    const timeLimit = 120000;

    // Reset the timer when the user interacts with the page
    document.addEventListener('mousemove', resetTimer);
    document.addEventListener('keypress', resetTimer);

    let timer;

    function resetTimer() {
      // Clear the existing timer
      clearTimeout(timer);
      // Start a new timer to redirect to login.php after the time limit has passed
      timer = setTimeout(redirectToLogin, timeLimit);
    }

    function redirectToLogin() {
      window.location.href = 'login.php';
    }
  </script>

  <div class="card">
    <div class="card-header">
      <div class="name">Employee List</div>
    </div>

    <div class="card-body">
      <?php
      session_start();

      if (!isset($_SESSION['username'])) { // Redirect to login page if user is not logged in

        // Function to connect to the database
        function connect_to_db()
        {
          $host = "localhost";
          $user = "anouar";
          $password = NULL;
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
          echo '<table class="table table-dark">
          <tr>
          <th>Name</th>
          <th>Function</th>
          <th>Salary</th>
          <th>Remove</th>
          <th>Update</th>
          </tr>';

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
          echo "</table>";
        } else {
          echo "<tr><td colspan='5'>0 results</td></tr>";
        }

        // Close database connection
        $conn->close();
        ?>

      <div class="button-container">
        <button type='button' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#logoutModal'>Logout</button>
        <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#myModal'>
          Add New
        </button>
      </div>
    </div>
  </div>

  <!-- Add New Employee Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add New Employee</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="color">
          <form method="POST" action="assessment-1.php">
            <div class="modal-body">
              <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter employee name" required>
              </div>
              <div class="mb-3">
                <label for="function" class="form-label">Function</label>
                <input type="text" class="form-control" id="function" name="function" placeholder="Enter employee function" required>
              </div>
              <div class="mb-3">
                <label for="salary" class="form-label">Salary</label>
                <input type="number" class="form-control" id="salary" name="salary" placeholder="Enter employee salary" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Add</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Logout modal -->
  <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="logoutModalLabel">WARNING</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to logout?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="confirmLogout">Logout</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Handle logout and redirection
    document.getElementById("confirmLogout").addEventListener("click", function() {
      // Clear session data
      fetch("logout.php")
        .then(() => {
          // Redirect to login page with zero history
          window.location.replace("login.php");
        })
        .catch((error) => console.error(error));
    });
  </script>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
<?php
} else {
  // User is not logged in, redirect to login page
  header("Location: assessment-1.php");
  exit();
}
?>