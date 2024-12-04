<?php
// Koneksi ke database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'inventorychristo';

// Membuat koneksi
$conn = new mysqli($host, $user, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session untuk keranjang belanja
session_start();

// Menampilkan data barang dari tabel 'tabelbarang'
$query_barang = "SELECT * FROM tabelbarang";
$result_barang = $conn->query($query_barang);

// Menampilkan data customer untuk dropdown
$query_customer = "SELECT * FROM customer";
$result_customer = $conn->query($query_customer);

// Menambahkan barang ke keranjang belanja
if (isset($_POST['add_to_cart'])) {
    $idbarang = $_POST['idbarang'];
    $jumlah = $_POST['jumlah'];

    // Ambil detail barang berdasarkan idbarang
    $query_barang_detail = "SELECT * FROM tabelbarang WHERE idbarang = '$idbarang'";
    $result_barang_detail = $conn->query($query_barang_detail);
    $barang = $result_barang_detail->fetch_assoc();

    // Menambahkan barang ke dalam session keranjang
    $harga_jual = $barang['hargajual'];
    $total_harga = $harga_jual * $jumlah;

    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }

    $_SESSION['keranjang'][] = [
        'idbarang' => $idbarang,
        'namabarang' => $barang['namabarang'],
        'jumlah' => $jumlah,
        'hargajual' => $harga_jual,
        'total' => $total_harga
    ];

    // Update total harga dalam session
    $_SESSION['total_harga'] = array_sum(array_column($_SESSION['keranjang'], 'total'));
}

// Proses checkout (insert ke tabel penjualan dan tabelpenjualan)
if (isset($_POST['checkout'])) {
    // Ambil data customer dari form
    $idcustomer = $_POST['idcustomer'];
    $total = $_SESSION['total_harga'];
    $tanggal = date('Y-m-d H:i:s');

    // Basic form validation
    if (empty($idcustomer)) {
        echo "<div class='alert alert-danger' role='alert'>Error: Please select a customer.</div>"; 
    } else {
        // Insert data ke tabel penjualan
        $query_penjualan = "INSERT INTO penjualan (tanggal, idcustomer, total) VALUES ('$tanggal', '$idcustomer', '$total')";
        if ($conn->query($query_penjualan) === TRUE) {
            $idpenjualan = $conn->insert_id; 

            // Insert detail penjualan ke tabelpenjualan 
            foreach ($_SESSION['keranjang'] as $item) {
                $idbarang = $item['idbarang'];
                $qty = $item['jumlah'];
                $hargajual = $item['hargajual'];

                $query_detail_penjualan = "INSERT INTO detailpenjualan (idpenjualan, idbarang, qty, hargajual) 
                                            VALUES ('$idpenjualan', '$idbarang', '$qty', '$hargajual')";
                $conn->query($query_detail_penjualan);
            }

            // Clear keranjang belanja setelah checkout
            unset($_SESSION['keranjang']);
            unset($_SESSION['total_harga']);
            echo "<div class='alert alert-success' role='alert'>Transaksi berhasil! Penjualan ID: " . $idpenjualan . "</div>";
        } else {
            // More informative error handling
            if ($conn->errno === 1452) { // Error code for foreign key constraint violation
                echo "<div class='alert alert-danger' role='alert'>Error: Invalid customer selected.</div>"; 
            } else {
                echo "<div class='alert alert-danger' role='alert'>Error: " . $conn->error . "</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Kasir</h2>
        
        <form action="" method="post">
            <div class="mb-3">
                <label for="idcustomer" class="form-label">Pilih Customer</label>
                <select name="idcustomer" id="idcustomer" class="form-select">
                    <option value="">Pilih Customer</option>
                    <?php while ($row_customer = $result_customer->fetch_assoc()) { ?>
                        <option value="<?= $row_customer['idcustomer'] ?>"><?= $row_customer['namacustomer'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <h3>Daftar Barang</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($barang = $result_barang->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $barang['idbarang'] ?></td>
                            <td><?= $barang['namabarang'] ?></td>
                            <td><?= $barang['kategori'] ?></td>
                            <td><?= number_format($barang['hargajual'], 0, ',', '.') ?></td>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="idbarang" value="<?= $barang['idbarang'] ?>">
                                    <input type="number" name="jumlah" value="1" min="1" class="form-control w-25" required>
                                    <button type="submit" name="add_to_cart" class="btn btn-primary btn-sm mt-2">Tambah ke Keranjang</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <h3>Keranjang Belanja</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($_SESSION['keranjang']) && count($_SESSION['keranjang']) > 0) {
                        foreach ($_SESSION['keranjang'] as $item) { ?>
                            <tr>
                                <td><?= $item['namabarang'] ?></td>
                                <td><?= $item['jumlah'] ?></td>
                                <td><?= number_format($item['hargajual'], 0, ',', '.') ?></td>
                                <td><?= number_format($item['total'], 0, ',', '.') ?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total Harga</strong></td>
                            <td><strong><?= number_format($_SESSION['total_harga'], 0, ',', '.') ?></strong></td>
                        </tr>
                    <?php } else { ?>
                        <tr><td colspan="4" class="text-center">Keranjang kosong</td></tr>
                    <?php } ?>
                </tbody>
            </table>

            <?php if (isset($_SESSION['keranjang']) && count($_SESSION['keranjang']) > 0) { ?>
                <button type="submit" name="checkout" class="btn btn-success">Checkout</button>
            <?php } ?>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>