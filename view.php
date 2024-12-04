<?php
include 'db.php'; // Include database connection
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
        $conn->query($sql);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk</title>
    <style>
        /* Basic styles for product display */
        .product-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .product {
            width: 200px;
            border: 1px solid #ddd;
            border-radius: 10px;
            text-align: center;
            padding: 10px;
            transition: transform 0.3s ease;
            overflow: hidden;
        }
        .product img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }
        .product:hover img {
            transform: scale(1.1); /* Zoom effect on hover */
        }
        .product-details {
            margin-top: 10px;
        }
        .product:hover {
            transform: scale(1.05);
        }
        .button {
            margin-top: 10px;
            background-color: #007bff;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="product-container">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="product">';
            
            echo '<div class="product-details">';
            echo '<h4>' . $row['namabarang'] . '</h4>';
            echo '<p>Harga: ' . number_format($row['hargajual'], 0, ',', '.') . '</p>';
            echo "<td><img src='uploads" . $row['gambar'] . "' width='100'></td>";
    
            echo '</form>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo "<p>No products available</p>";
    }
    ?>
</div>

</body>
</html>
