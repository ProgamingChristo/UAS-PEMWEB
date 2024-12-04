<?php
include 'db.php';
include 'header.php';
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        $sql = "INSERT INTO User (username, password, role) VALUES ('$username', '$password', '$role')";
        if ($conn->query($sql) === TRUE) {
            $alert = "<div class='alert alert-success' role='alert'>Pengguna berhasil ditambahkan!</div>";
        } else {
            $alert = "<div class='alert alert-danger' role='alert'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    } elseif (isset($_POST['update'])) {
        $iduser = $_POST['iduser'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        $sql = "UPDATE User SET username='$username', password='$password', role='$role' WHERE iduser='$iduser'";
        if ($conn->query($sql) === TRUE) {
            $alert = "<div class='alert alert-success' role='alert'>Pengguna berhasil diubah!</div>";
        } else {
            $alert = "<div class='alert alert-danger' role='alert'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    } elseif (isset($_POST['delete'])) {
        $iduser = $_POST['iduser'];

        // Hapus entri dari tabel akses_user terlebih dahulu
        $sql = "DELETE FROM akses_user WHERE iduser='$iduser'";
        $conn->query($sql);

        // Kemudian hapus entri dari tabel User
        $sql = "DELETE FROM User WHERE iduser='$iduser'";
        if ($conn->query($sql) === TRUE) {
            $alert = "<div class='alert alert-success' role='alert'>Pengguna berhasil dihapus!</div>";
        } else {
            $alert = "<div class='alert alert-danger' role='alert'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    }
}
$sql = "SELECT * FROM User";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna - Inventory System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Manajemen Pengguna</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>".$row["username"]."</td>
                                <td>".$row["role"]."</td>
                                <td>
                                    <button class='btn btn-warning btn-sm' onclick='editUser(".$row["iduser"].", \"".$row["username"]."\", \"".$row["password"]."\", \"".$row["role"]."\")'>Edit</button>
                                    <form method='POST' action='customer.php' class='d-inline'>
                                        <input type='hidden' name='iduser' value='".$row["iduser"]."'>
                                        <button type='submit' name='delete' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah kamu yakin ingin menghapus pengguna ini?\")'>Delete</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                }
                ?>
            </tbody>
        </table>

        <h2 class="mt-5" id="form-title">Tambah Pengguna</h2>
        <form method="POST" action="customer.php" id="userForm">
            <input type="hidden" id="iduser" name="iduser">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="Admin">Admin</option>
                    <option value="Kasir">Kasir</option>
                    <option value="Supplier">Suppervisor</option>
                </select>
            </div>
            <button type="submit" name="add" class="btn btn-primary">Tambah Pengguna</button>
            <button type="submit" name="update" class="btn btn-success" id="updateBtn" style="display:none;">Update Pengguna</button>
        </form>
    </div>

    <!-- Bootstrap 5 JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script>
    function editUser(id, username, password, role) {
        document.getElementById('iduser').value = id;
        document.getElementById('username').value = username;
        document.getElementById('password').value = password;
        document.getElementById('role').value = role;
        document.getElementById('form-title').innerText = "Edit Pengguna";
        document.getElementById('updateBtn').style.display = 'inline-block';
    }
    </script>
</body>
</html>
