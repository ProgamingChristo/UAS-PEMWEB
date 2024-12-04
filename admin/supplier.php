<?php
include '../db.php';
include 'header.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        $namasupplier = $_POST['namasupplier'];
        $alamat = $_POST['alamat'];
        $telepon = $_POST['telepon'];

        $sql = "INSERT INTO Supplier (namasupplier, alamat, telepon) VALUES ('$namasupplier', '$alamat', '$telepon')";
        if ($conn->query($sql) === TRUE) {
            $alert = "<div class='alert alert-success' role='alert'>Supplier berhasil ditambahkan!</div>";
        } else {
            $alert = "<div class='alert alert-danger' role='alert'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    } elseif (isset($_POST['update'])) {
        $idsupplier = $_POST['idsupplier'];
        $namasupplier = $_POST['namasupplier'];
        $alamat = $_POST['alamat'];
        $telepon = $_POST['telepon'];

        $sql = "UPDATE Supplier SET namasupplier='$namasupplier', alamat='$alamat', telepon='$telepon' WHERE idsupplier='$idsupplier'";
        if ($conn->query($sql) === TRUE) {
            $alert = "<div class='alert alert-success' role='alert'>Supplier berhasil diubah!</div>";
        } else {
            $alert = "<div class='alert alert-danger' role='alert'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    } elseif (isset($_POST['delete'])) {
        $idsupplier = $_POST['idsupplier'];

        // Set NULL kunci asing pada tabel pembelian sebelum menghapus supplier
        $sql = "UPDATE pembelian SET idsupplier=NULL WHERE idsupplier='$idsupplier'";
        $conn->query($sql);

        // Kemudian hapus entri dari tabel Supplier
        $sql = "DELETE FROM Supplier WHERE idsupplier='$idsupplier'";
        if ($conn->query($sql) === TRUE) {
            $alert = "<div class='alert alert-success' role='alert'>Supplier berhasil dihapus!</div>";
        } else {
            $alert = "<div class='alert alert-danger' role='alert'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    }
}

$sql = "SELECT * FROM Supplier";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier - Inventory</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Daftar Supplier</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nama Supplier</th>
                    <th>Alamat</th>
                    <th>Telepon</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>".$row["namasupplier"]."</td>
                                <td>".$row["alamat"]."</td>
                                <td>".$row["telepon"]."</td>
                                <td>
                                    <button class='btn btn-warning btn-sm' onclick='editSupplier(".$row["idsupplier"].", \"".$row["namasupplier"]."\", \"".$row["alamat"]."\", \"".$row["telepon"]."\")'>Edit</button>
                                    <form method='POST' action='supplier.php' class='d-inline'>
                                        <input type='hidden' name='idsupplier' value='".$row["idsupplier"]."'>
                                        <button type='submit' name='delete' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah kamu yakin ingin menghapus supplier ini?\")'>Delete</button>
                                    </form>
                                </td>
                              </tr>";
                     }
                }
                ?>
            </tbody>
        </table>
        <h2 class="mt-5" id="form-title">Tambah Supplier</h2>
        <form method="POST" action="supplier.php" id="supplierForm">
            <input type="hidden" id="idsupplier" name="idsupplier">
            <div class="mb-3">
                <label for="namasupplier" class="form-label">Nama Supplier</label>
                <input type="text" class="form-control" id="namasupplier" name="namasupplier" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <input type="text" class="form-control" id="alamat" name="alamat" required>
            </div>
            <div class="mb-3">
                <label for="telepon" class="form-label">Telepon</label>
                <input type="text" class="form-control" id="telepon" name="telepon" required>
            </div>
            <button type="submit" name="add" class="btn btn-primary">Tambah Supplier</button>
            <button type="submit" name="update" class="btn btn-success" id="updateBtn" style="display:none;">Update Supplier</button>
        </form>
    </div>

    <!-- Bootstrap 5 JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script>
    function editSupplier(id, nama, alamat, telepon) {
        document.getElementById('idsupplier').value = id;
        document.getElementById('namasupplier').value = nama;
        document.getElementById('alamat').value = alamat;
        document.getElementById('telepon').value = telepon;
        document.getElementById('form-title').innerText = "Edit Supplier";
        document.getElementById('updateBtn').style.display = 'inline-block';
    }
    </script>
</body>
</html>
