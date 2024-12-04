<?php
include '../db.php';
include 'header1.php';

// Fetch all items from the database
$sql = "SELECT * FROM TabelBarang";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Barang</title>
    <!-- Add Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

    <div class="container mx-auto p-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Daftar Barang</h2>

        <!-- Check if any items are available -->
        <?php if ($result->num_rows > 0): ?>
            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Nama Barang</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Kategori</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Harga Beli</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Harga Jual</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="border-b">
                                <td class="px-4 py-2 text-sm text-gray-700"><?php echo $row['namabarang']; ?></td>
                                <td class="px-4 py-2 text-sm text-gray-700"><?php echo $row['kategori']; ?></td>
                                <td class="px-4 py-2 text-sm text-gray-700"><?php echo number_format($row['hargabeli'], 0, ',', '.'); ?></td>
                                <td class="px-4 py-2 text-sm text-gray-700"><?php echo number_format($row['hargajual'], 0, ',', '.'); ?></td>
                                <td class="px-4 py-2 text-sm text-gray-700"><?php echo $row['stok']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="mt-4 text-center text-gray-600">Tidak ada barang yang tersedia.</p>
        <?php endif; ?>
    </div>

</body>
</html>
