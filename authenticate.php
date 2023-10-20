<?php 
session_start();

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

$conn = connect_to_db();

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username)) {
        header("Location: login.php?error=Username is required");
    } else if (empty($password)){
        header("Location: login.php?error=Password is required&username=$username");
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $user_password = $user['password'];

            if ($username === $user['username']) {
                if (password_verify($password, $user_password)) {
                    $_SESSION['logged_in'] = true;
                    header("Location: assessment-1.php");

                } else {
                    header("Location: login.php?error= Username or password Incorrect&username=$username");
                }
            } else {
                header("Location: login.php?error= Username or password Incorrect&username=$username");
            }
        } else {
            header("Location: login.php?error= Username or password Incorrect&username=$username");
        }
    }
}  


