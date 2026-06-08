<?php 
require_once __DIR__ . '/../config/koneksi.php';

$editing = false;
$editKriteria = null;

if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    if ($deleteId > 0) {
        mysqli_query($conn, "DELETE FROM kriteria WHERE id = $deleteId");
    }
    header('Location: kriteria.php');
    exit;
}

if (isset($_GET['edit'])) {
    $editId = intval($_GET['edit']);
    if ($editId > 0) {
        $result = mysqli_query($conn, "SELECT * FROM kriteria WHERE id = $editId LIMIT 1");
        if ($result && mysqli_num_rows($result) > 0) {
            $editing = true;
            $editKriteria = mysqli_fetch_assoc($result);
        }
    }
}

if (isset($_POST['tambah'])) {
    $nama  = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $bobot = floatval($_POST['bobot']);
    $tipe  = mysqli_real_escape_string($conn, trim($_POST['tipe']));

    if ($nama !== '' && ($tipe === 'benefit' || $tipe === 'cost')) {
        mysqli_query($conn, "INSERT INTO kriteria (nama_kriteria, bobot, tipe) 
            VALUES ('$nama', '$bobot', '$tipe')");
    }
    header('Location: kriteria.php');
    exit;
}

if (isset($_POST['update'])) {
    $id    = intval($_POST['id']);
    $nama  = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $bobot = floatval($_POST['bobot']);
    $tipe  = mysqli_real_escape_string($conn, trim($_POST['tipe']));

    if ($id > 0 && $nama !== '' && ($tipe === 'benefit' || $tipe === 'cost')) {
        mysqli_query($conn, "UPDATE kriteria SET nama_kriteria = '$nama', bobot = '$bobot', tipe = '$tipe' WHERE id = $id");
    }
    header('Location: kriteria.php');
    exit;
}

$countKriteria = mysqli_query($conn, "SELECT COUNT(*) as total FROM kriteria");
$kriteriaTotal = mysqli_fetch_assoc($countKriteria);

Require_once __DIR__ . '/../layout/sidebar.php';
?>

<div class="content">
    <div class="page-header">
        <div>
            <h2>📊 Data Kriteria</h2>
            <p class="subtitle">Kelola kriteria penilaian untuk metode SAW. Tambahkan bobot dan tipe benefit/cost agar perhitungan ranking berjalan akurat.</p>
        </div>
    </div>

    <div class="card-grid">
        <div class="stat-card">
            <p class="eyebrow">Total Kriteria</p>
            <h3><?= $kriteriaTotal['total'] ?></h3>
            <p>Atur kriteria yang akan digunakan saat menilai smartphone.</p>
        </div>
    </div>

    <div class="card">
        <form method="POST" class="form-box">
            <div class="form-group">
                <label for="nama">Nama Kriteria</label>
                <input id="nama" class="input-field" type="text" name="nama" placeholder="Contoh: Harga" value="<?= $editing ? htmlspecialchars($editKriteria['nama_kriteria'], ENT_QUOTES, 'UTF-8') : '' ?>">
            </div>
            <div class="form-group">
                <label for="bobot">Bobot</label>
                <input id="bobot" class="input-field" type="number" step="0.01" name="bobot" placeholder="Contoh: 0.25" value="<?= $editing ? htmlspecialchars($editKriteria['bobot'], ENT_QUOTES, 'UTF-8') : '' ?>">
            </div>
            <div class="form-group">
                <label for="tipe">Tipe</label>
                <select id="tipe" class="input-field" name="tipe" required>
                    <option value="">Pilih Tipe</option>
                    <option value="benefit" <?= $editing && $editKriteria['tipe'] === 'benefit' ? 'selected' : '' ?>>Benefit</option>
                    <option value="cost" <?= $editing && $editKriteria['tipe'] === 'cost' ? 'selected' : '' ?>>Cost</option>
                </select>
            </div>
            <div class="action-buttons">
                <?php if ($editing && $editKriteria): ?>
                    <input type="hidden" name="id" value="<?= $editKriteria['id'] ?>">
                    <button class="btn btn-success" name="update">Perbarui</button>
                    <a href="kriteria.php" class="btn btn-dark">Batal</a>
                <?php else: ?>
                    <button class="btn btn-success" name="tambah">Tambah</button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kriteria</th>
                        <th>Bobot</th>
                        <th>Tipe</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $data = mysqli_query($conn, "SELECT * FROM kriteria");
                    while ($d = mysqli_fetch_assoc($data)) {
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($d['nama_kriteria'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($d['bobot'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <span class="btn <?= $d['tipe']=='benefit' ? 'btn-success' : 'btn-danger' ?>">
                                <?= htmlspecialchars($d['tipe'], ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="kriteria.php?edit=<?= $d['id'] ?>" class="btn btn-success btn-sm">Edit</a>
                                <a href="kriteria.php?delete=<?= $d['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus kriteria <?= htmlspecialchars($d['nama_kriteria'], ENT_QUOTES, 'UTF-8') ?>?');">Hapus</a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>