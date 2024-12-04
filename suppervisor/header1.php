<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory System</title>
    <!-- Bootstrap CSS (v4.5) -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <style>
        /* Sidebar styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
        }

        .sidebar .nav-link {
            color: white;
            font-weight: 500;
        }

        .sidebar .nav-link:hover {
            background-color: #495057;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .navbar {
            margin-left: 250px;
        }
    </style>
    <!-- Bootstrap JS (v4.5) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center text-white">KUKUHRIA</h4>
        <h5 class="text-center text-yellow">Hai Suppervisor</h5>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="../index.php">Logout</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="barang1.php">Barang</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="supplier1.php">Supplier</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="customer1.php">Customer</a>
            </li>
            
        </ul>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <!-- Top Navbar -->

    <!-- Bootstrap JS (v4.5) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>