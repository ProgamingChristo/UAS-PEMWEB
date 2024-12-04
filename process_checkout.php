<?php
include 'db.php';
session_start();

$data = json_decode(file_get_contents('php://input'), true);
$iduser = $_SESSION['iduser'];

if (!empty($data)) {
    // Buat pesanan baru
    $sql = "INSERT INTO Pesanan (iduser) VALUES ('$iduser')";
    if ($conn->query($sql) === TRUE) {
        $idpesanan = $conn->insert_id;
        
        // Tambahkan detail pesanan
        foreach ($data as $item) {
            $idbarang = $item['id'];
            $jumlah = $item['quantity'];
            $harga = $item['price'];
            
            $sql = "INSERT INTO DetailPesanan (idpesanan, idbarang, jumlah, harga) VALUES ('$idpesanan', '$idbarang', '$jumlah', '$harga')";
            $conn->query($sql);
        }
        
        echo json_encode(["message" => "Pesanan berhasil dibuat dan menunggu persetujuan kasir."]);
    } else {
        echo json_encode(["message" => "Gagal membuat pesanan."]);
    }
} else {
    echo json_encode(["message" => "Tidak ada data dalam keranjang."]);
}
?>
