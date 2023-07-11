<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../img/logo-title/logo-title.jpg" type="image/gif">
    <title>Input Data - Ahli Shutter</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<?php
if (!isset($_SERVER['HTTP_REFERER']) || !strpos($_SERVER['HTTP_REFERER'], 'portal-admin.php')) {
    $pesan = "Anda tidak boleh memasuki halaman ini tanpa LOGIN !";

    echo '<script type="text/javascript">';
    echo 'alert("' . $pesan . '");';
    echo '</script>';

    header('Location: portal-admin.php');
}
?>

<body>
    <div class="container">
        <h2 class="mt-4">Ahli Shutter - Admin</h2>
        <form method="post" enctype="multipart/form-data" class="mt-4">
            <div class="form-group">
                <label for="kategori">Kategori:</label>
                <select name="kategori" id="kategori" class="form-control">
                    <option value="DSLR">DSLR</option>
                    <option value="Mirrorless">Mirrorless</option>
                </select>
            </div>

            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" name="nama" id="nama" class="form-control">
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi:</label>
                <textarea name="deskripsi" id="deskripsi" rows="5" cols="40" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="harga">Harga:</label>
                <input type="text" name="harga" id="harga" class="form-control">
            </div>

            <div class="form-group">
                <label for="gambar">Upload Gambar:</label>
                <input type="file" name="gambar" id="gambar" class="form-control-file">
            </div>

            <input type="submit" name="submit" value="Submit" class="btn btn-primary mt-3">
        </form>

<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        $kategori = $_POST['kategori'];
        $nama = $_POST['nama'];
        $deskripsi = $_POST['deskripsi'];
        $harga = $_POST['harga'];

        // Upload gambar
        $gambarName = $_FILES['gambar']['name'];
        $gambarTmpName = $_FILES['gambar']['tmp_name'];
        $gambarPath = '../img/stock/' . $gambarName;
        move_uploaded_file($gambarTmpName, $gambarPath);

        // Baca data JSON yang ada
        $jsonString = file_get_contents('../data/barang.json');
        $data = json_decode($jsonString, true);

        // Buat array untuk data barang baru
        $barang = array(
            'kategori' => $kategori,
            'nama' => $nama,
            'deskripsi' => $deskripsi,
            'harga' => $harga,
            'gambar' => $gambarName
        );

        // Tambahkan data barang baru ke dalam array
        $data['barang'][] = $barang;

        // Convert array ke format JSON
        $json = json_encode($data, JSON_PRETTY_PRINT);

        // Simpan JSON ke file
        file_put_contents('../data/barang.json', $json);

        echo '<p>Data barang berhasil disimpan.</p>';

        header('Location: admin.php');
    }

    // Hapus data barang
    if (isset($_GET['index']) && $_SERVER['REQUEST_METHOD'] !== 'POST') { // Tambahkan kondisi untuk tidak menjalankan skrip hapus jika ada pengiriman formulir
        $hapusIndex = $_GET['index'];

        // Baca data JSON yang ada
        $jsonString = file_get_contents('../data/barang.json');
        $data = json_decode($jsonString, true);

        // Hapus data dengan indeks yang diberikan
        if (isset($data['barang'][$hapusIndex])) {
            unset($data['barang'][$hapusIndex]);

            // Reset indeks array setelah penghapusan
            $data['barang'] = array_values($data['barang']);

            // Convert array ke format JSON
            $json = json_encode($data, JSON_PRETTY_PRINT);

            // Simpan JSON ke file
            file_put_contents('../data/barang.json', $json);

            echo '<p>Data barang berhasil dihapus.</p>';
        }
    }

    // Menampilkan data barang
    $jsonString = file_get_contents('../data/barang.json');
    $data = json_decode($jsonString, true);
?>

    <?php if (!empty($data['barang'])) : ?>
        <h2 class="mt-5">Data Barang</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Harga</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        <tbody>
            <?php foreach ($data['barang'] as $index => $barang) : ?>
                <tr>
                    <td><?= $barang['kategori']; ?></td>
                    <td><?= $barang['nama']; ?></td>
                    <td><?= $barang['deskripsi']; ?></td>
                    <td><?= $barang['harga']; ?></td>
                    <td><img src="../img/stock/<?= $barang['gambar']; ?>" width="100"></td>
                    <td><a href="?index=<?= $index; ?>" class="btn btn-danger btn-sm" data-index="<?= $index; ?>">Hapus</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>Tidak ada data barang.</p>
    <?php endif; ?>
</div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
