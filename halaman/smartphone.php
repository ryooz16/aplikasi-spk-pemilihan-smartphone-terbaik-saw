<?php 
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/koneksi.php';

$editing = false;
$editSmartphone = null;

if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    if ($deleteId > 0) {
        mysqli_query($conn, "DELETE FROM smartphone WHERE id = $deleteId");
    }
    header('Location: smartphone.php');
    exit;
}

if (isset($_GET['edit'])) {
    $editId = intval($_GET['edit']);
    if ($editId > 0) {
        $result = mysqli_query($conn, "SELECT * FROM smartphone WHERE id = $editId LIMIT 1");
        if ($result && mysqli_num_rows($result) > 0) {
            $editing = true;
            $editSmartphone = mysqli_fetch_assoc($result);
        }
    }
}

if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, trim($_POST['nama']));
    if ($nama !== '') {
        mysqli_query($conn, "INSERT INTO smartphone (nama_smartphone) VALUES ('$nama')");
        header('Location: smartphone.php');
        exit;
    }
}

if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $nama = mysqli_real_escape_string($conn, trim($_POST['nama']));
    if ($id > 0 && $nama !== '') {
        mysqli_query($conn, "UPDATE smartphone SET nama_smartphone = '$nama' WHERE id = $id");
        header('Location: smartphone.php');
        exit;
    }
}

$countSmartphone = mysqli_query($conn, "SELECT COUNT(*) as total FROM smartphone");
$smartphoneTotal = mysqli_fetch_assoc($countSmartphone);

$data = mysqli_query($conn, "SELECT * FROM smartphone ORDER BY id ASC");
if ($data) {
    $smartphones = mysqli_fetch_all($data, MYSQLI_ASSOC);
} else {
    $smartphones = [];
}

Require_once __DIR__ . '/../layout/sidebar.php';
?>

<div class="content">
    <div class="page-header">
        <div>
            <h2>📱 Data Smartphone</h2>
            <p class="subtitle">Tambah dan kelola daftar smartphone yang akan digunakan dalam proses penilaian SAW. Halaman ini menampilkan semua data smartphone secara ringkas dan mudah dibaca.</p>
        </div>
    </div>

    <div class="card-grid">
        <div class="stat-card">
            <p class="eyebrow">Total Smartphone</p>
            <h3><?= $smartphoneTotal['total'] ?></h3>
            <p>Jumlah smartphone yang sudah terdaftar pada sistem.</p>
        </div>
    </div>

    <div class="card">
        <form method="POST" class="form-box">
            <div class="form-group">
                <label for="nama">Nama Smartphone</label>
                <input id="nama" class="input-field" type="text" name="nama" placeholder="Contoh: Samsung Galaxy A54" value="<?= $editing ? htmlspecialchars($editSmartphone['nama_smartphone'], ENT_QUOTES, 'UTF-8') : '' ?>" required>
            </div>
            <div class="action-buttons">
                <?php if ($editing && $editSmartphone): ?>
                    <input type="hidden" name="id" value="<?= $editSmartphone['id'] ?>">
                    <button class="btn btn-success" type="submit" name="update">Perbarui</button>
                    <a href="smartphone.php" class="btn btn-dark">Batal</a>
                <?php else: ?>
                    <button class="btn btn-primary" type="submit" name="tambah">Tambah</button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="table-responsive">
            <?php if (count($smartphones) === 0): ?>
                <p class="no-data">Belum ada data smartphone. Tambahkan data terlebih dahulu untuk melihat daftar.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Nama Smartphone</th>
                            <th style="width: 190px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($smartphones as $index => $d): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($d['nama_smartphone'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="smartphone.php?edit=<?= $d['id'] ?>" class="btn btn-success btn-sm">Edit</a>
                                        <a href="smartphone.php?delete=<?= $d['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data <?= htmlspecialchars($d['nama_smartphone'], ENT_QUOTES, 'UTF-8') ?>?');">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>