<?php
include '../db.php'; // Include database connection
include 'header.php'; // Include header

// Fetch existing products from the database
$sql = "SELECT * FROM TabelBarang";
$result = $conn->query($sql);

// Fetch categories for dropdown
$sqlKategori = "SELECT DISTINCT kategori FROM TabelBarang";
$resultKategori = $conn->query($sqlKategori);

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        addProduct($conn);
    } elseif (isset($_POST['edit'])) {
        editProduct($conn);
    } elseif (isset($_POST['delete'])) {
        deleteProduct($conn);
    }
}

// Function to add a new product
function addProduct($conn) {
    if (isset($_POST['namabarang'], $_POST['kategori'], $_POST['hargabeli'], $_POST['hargajual'], $_POST['stok'])) {
        $namabarang = $_POST['namabarang'];
        $kategori = $_POST['kategori'];
        $hargabeli = $_POST['hargabeli'];
        $hargajual = $_POST['hargajual'];
        $stok = $_POST['stok'];

        // Handle file upload
        $imageName = uploadImage();

        // Insert product data along with the image name
        $sql = "INSERT INTO TabelBarang (namabarang, kategori, hargabeli, hargajual, stok, gambar) 
                VALUES ('$namabarang', '$kategori', '$hargabeli', '$hargajual', '$stok', '$imageName')";

        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success' role='alert'>Barang berhasil ditambahkan!</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger' role='alert'>Form data tidak lengkap!</div>";
    }
}

// Function to edit an existing product
function editProduct($conn) {
    if (isset($_POST['idbarang'], $_POST['namabarang'], $_POST['kategori'], $_POST['hargabeli'], $_POST['hargajual'], $_POST['stok'])) {
        $idbarang = $_POST['idbarang'];
        $namabarang = $_POST['namabarang'];
        $kategori = $_POST['kategori'];
        $hargabeli = $_POST['hargabeli'];
        $hargajual = $_POST['hargajual'];
        $stok = $_POST['stok'];

        // Handle image upload
        $imageName = uploadImage();
        if ($imageName == "") {
            $currentProduct = $conn->query("SELECT gambar FROM TabelBarang WHERE idbarang = '$idbarang'")->fetch_assoc();
            $imageName = $currentProduct['gambar'];
        }

        // Update product data
        $sql = "UPDATE TabelBarang SET 
                namabarang='$namabarang', 
                kategori='$kategori', 
                hargabeli='$hargabeli', 
                hargajual='$hargajual', 
                stok='$stok', 
                gambar='$imageName' 
                WHERE idbarang='$idbarang'";

        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success' role='alert'>Barang berhasil diupdate!</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger' role='alert'>Form data tidak lengkap!</div>";
    }
}

// Function to delete a product
function deleteProduct($conn) {
    $idbarang = $_POST['idbarang'];

    // Delete product data
    $sql = "DELETE FROM TabelBarang WHERE idbarang='$idbarang'";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success' role='alert'>Barang berhasil dihapus!</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $sql . "<br>" . $conn->error . "</div>";
    }
}

// Function to handle image upload
function uploadImage() {
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $fileTmpPath = $_FILES['gambar']['tmp_name'];
        $fileName = $_FILES['gambar']['name'];
        $fileSize = $_FILES['gambar']['size'];
        $fileType = $_FILES['gambar']['type'];

        // Validate file size (max 10MB)
        if ($fileSize > 10 * 1024 * 1024) {
            echo "<div class='alert alert-danger' role='alert'>File terlalu besar! Maksimum 10MB.</div>";
            return "";
        }

        // Validate file extension (only .jpg or .png)
        $allowedTypes = ['image/jpeg', 'image/png'];
        if (!in_array($fileType, $allowedTypes)) {
            echo "<div class='alert alert-danger' role='alert'>Hanya file .jpg atau .png yang diperbolehkan.</div>";
            return "";
        }

        // Generate a unique file name to avoid overwriting
        $imageName = time() . "_" . basename($fileName);
        $uploadDir = 'uploads'; // Make sure this directory exists and is writable
        $destPath = $uploadDir . $imageName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            return $imageName;
        } else {
            echo "<div class='alert alert-danger' role='alert'>Gagal mengupload gambar.</div>";
            return "";
        }
    }
    return ""; // Return empty if no image uploaded
}

?>

<div class="container">
    <h2>Daftar Barang</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Stok</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['idbarang'] . "</td>";
                    echo "<td>" . $row['namabarang'] . "</td>";
                    echo "<td>" . $row['kategori'] . "</td>";
                    echo "<td>" . $row['hargabeli'] . "</td>";
                    echo "<td>" . $row['hargajual'] . "</td>";
                    echo "<td>" . $row['stok'] . "</td>";
                    echo "<td><img src='../uploads" . $row['gambar'] . "' width='100'></td>"; // Add slash for correct path
                    echo "<td> 
                            <form method='POST' style='display:inline;'> 
                                <input type='hidden' name='idbarang' value='" . $row['idbarang'] . "'> 
                                <button type='submit' class='btn btn-warning' name='edit'>Edit</button> 
                                <button type='submit' class='btn btn-danger' name='delete'>Delete</button> 
                            </form> 
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>Tidak ada barang ditemukan</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <h2>Tambah Barang</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="namabarang">Nama Barang:</label>
            <input type="text" class="form-control" id="namabarang" name="namabarang" required>
        </div>
        <div class="form-group">
            <label for="kategori">Kategori:</label>
            <select class="form-control" id="kategori" name="kategori" required>
                <?php
                if ($resultKategori->num_rows > 0) {
                    while($rowKategori = $resultKategori->fetch_assoc()) {
                        echo "<option value='" . $rowKategori['kategori'] . "'>" . $rowKategori['kategori'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Tidak ada kategori ditemukan</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="hargabeli">Harga Beli:</label>
            <input type="number" class="form-control" id="hargabeli" name="hargabeli" required>
        </div>
        <div class="form-group">
            <label for="hargajual">Harga Jual:</label>
            <input type="number" class="form-control" id="hargajual" name="hargajual" required>
        </div>
        <div class="form-group">
            <label for="stok">Stok:</label>
            <input type="number" class="form-control" id="stok" name="stok" required>
        </div>
        <div class="form-group">
            <label for="gambar">Upload Gambar:</label>
            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/jpeg, image/png" required>
        </div>
        <button type="submit" class="btn btn-primary" name="add">Tambah Barang</button>
    </form>

    <?php
    // Populate edit form if edit button clicked
    if (isset($_POST['edit'])) {
        $idbarang = $_POST['idbarang'];
        $product = $conn->query("SELECT * FROM TabelBarang WHERE idbarang='$idbarang'")->fetch_assoc();
        if ($product) {
            ?>
            <h2>Edit Barang</h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="idbarang" value="<?= $product['idbarang']; ?>">
                <div class="form-group">
                    <label for="namabarang">Nama Barang:</label>
                    <input type="text" class="form-control" id="namabarang" name="namabarang" value="<?= $product['namabarang']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="kategori">Kategori:</label>
                    <select class="form-control" id="kategori" name="kategori" required>
                        <?php
                        // Fetch categories for dropdown
                        $sqlKategori = "SELECT DISTINCT kategori FROM TabelBarang";
                        $resultKategori = $conn->query($sqlKategori);
                        if ($resultKategori->num_rows > 0) {
                            while($rowKategori = $resultKategori->fetch_assoc()) {
                                $selected = ($rowKategori['kategori'] == $product['kategori']) ? "selected" : "";
                                echo "<option value='" . $rowKategori['kategori'] . "' $selected>" . $rowKategori['kategori'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>Tidak ada kategori ditemukan</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="hargabeli">Harga Beli:</label>
                    <input type="number" class="form-control" id="hargabeli" name="hargabeli" value="<?= $product['hargabeli']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="hargajual">Harga Jual:</label>
                    <input type="number" class="form-control" id="hargajual" name="hargajual" value="<?= $product['hargajual']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="stok">Stok:</label>
                    <input type="number" class="form-control" id="stok" name="stok" value="<?= $product['stok']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="gambar">Upload Gambar Baru:</label>
                    <input type="file" class="form-control" id="gambar" name="gambar" accept="image/jpeg, image/png">
                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
                </div>
                <button type="submit" class="btn btn-warning" name="edit">Update Barang</button>
            </form>
            <?php
        }
    }
    ?>
</div>
</body>
</html>