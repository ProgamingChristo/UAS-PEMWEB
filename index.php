<?php
$servername = "localhost"; // or your server address if using a remote DB
$username = "root";        // replace with your MySQL username
$password = "";            // replace with your MySQL password
$dbname = "inventorychristo"; // replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$alert = ""; // Initialize the alert variable

// Handle login logic here, if any
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the POST request
    $username = $conn->real_escape_string($_POST['username']); // Prevent SQL injection
    $password = $_POST['password'];

    // Prepare and execute query to check for the user
    $sql = "SELECT * FROM user WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Direct password comparison (no hashing)
        if ($password == $row['password']) {
            // Start session and set session variables
            session_start();
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['iduser'] = $row['iduser'];

            // Redirect based on the user role
            switch ($row['role']) {
                case 'Admin':
                    header("Location: ../admin/header.php");
                    break;
                case 'Kasir':
                    header("Location: ../kasir/kasir.php");
                    break;
                case 'Supervisor':
                    header("Location: ../suppervisor/header1.php");
                    break;
                default:
                    $alert = "<div class='alert alert-danger' role='alert'>Peran pengguna tidak valid.</div>";
            }
            exit(); // Prevent further code execution after redirect
        } else {
            $alert = "<div class='alert alert-danger' role='alert'>Username atau password salah.</div>";
        }
    } else {
        $alert = "<div class='alert alert-danger' role='alert'>Username atau password salah.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS (v4.5) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        /* Full height container */
        .container-full {
            height: 100vh;
        }

        /* Left side with blue background */
        .left-side {
            background-color: #007bff; /* Blue color */
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Right side with form */
        .right-side {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-form {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
        }

        /* Ensure the image is responsive */
        .left-side img {
            max-width: 100%;
            height: auto;
        }

        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid container-full">
        <div class="row w-100 h-100">
            <!-- Left Side (Blue Background and Image) -->
            <div class="col-md-6 left-side">
                <img src="/Inventory%20(2)/Inventory/img/gambar.png" alt="Image" class="img-fluid">
            </div>

            <!-- Right Side (Login Form) -->
            <div class="col-md-6 right-side">
                <div class="login-form">
                    <h3 class="text-center mb-4">Login</h3>

                    <?php if ($alert) echo $alert; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (v4.5) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>