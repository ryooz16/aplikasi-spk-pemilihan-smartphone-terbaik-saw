<?php 
require_once __DIR__ . '/../config/koneksi.php';

$editing = false;
$editSmartphone = null;
$selectedSmartphone = '';
$inputHarga = '';
$inputRam = '';
$inputBenchmark = '';
$inputKamera = '';
$inputStorage = '';

function konversiBenchmark($score) {
    if ($score >= 1500000) return 5;
    if ($score >= 1000000) return 4;
    if ($score >= 700000) return 3;
    if ($score >= 400000) return 2;
    return 1;
}

function konversiRAM($ram) {
    if ($ram >= 12) return 5;
    if ($ram >= 8) return 4;
    if ($ram >= 6) return 3;
    if ($ram >= 4) return 2;
    return 1;
}

function konversiStorage($storage) {
    if ($storage >= 256) return 5;
    if ($storage >= 128) return 4;
    if ($storage >= 64) return 3;
    if ($storage >= 32) return 2;
    return 1;
}

function konversiKamera($kamera) {
    if ($kamera >= 108) return 5;
    if ($kamera >= 64) return 4;
    if ($kamera >= 48) return 3;
    if ($kamera >= 13) return 2;
    return 1;
}

function konversiHarga($harga) {
    if ($harga <= 2000000) return 5;
    if ($harga <= 4000000) return 4;
    if ($harga <= 6000000) return 3;
    if ($harga <= 8000000) return 2;
    return 1;
}

if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    if ($deleteId > 0) {
        mysqli_query($conn, "DELETE FROM nilai WHERE id_smartphone = $deleteId");
        mysqli_query($conn, "DELETE FROM spesifikasi WHERE id_smartphone = $deleteId");
    }
    header('Location: nilai.php');
    exit;
}

if (isset($_GET['edit'])) {
    $editId = intval($_GET['edit']);
    if ($editId > 0) {
        $result = mysqli_query($conn, "SELECT * FROM spesifikasi WHERE id_smartphone = $editId LIMIT 1");
        if ($result && mysqli_num_rows($result) > 0) {
            $editing = true;
            $editSmartphone = mysqli_fetch_assoc($result);
            $selectedSmartphone = $editSmartphone['id_smartphone'];
            $inputHarga = $editSmartphone['harga'];
            $inputRam = $editSmartphone['ram'];
            $inputBenchmark = $editSmartphone['benchmark_antutu'];
            $inputKamera = $editSmartphone['kamera'];
            $inputStorage = $editSmartphone['storage'];
        }
    }
}

if (isset($_POST['simpan'])) {
    $id = intval($_POST['smartphone']);
    $harga = intval($_POST['harga']);
    $ram = intval($_POST['ram']);
    $benchmark = intval($_POST['benchmark_antutu']);
    $kamera = intval($_POST['kamera']);
    $storage = intval($_POST['storage']);

    if ($id > 0) {
        mysqli_query($conn, "DELETE FROM nilai WHERE id_smartphone = $id");

        $nilai = [
            1 => konversiHarga($harga),
            2 => konversiRAM($ram),
            3 => konversiBenchmark($benchmark),
            4 => konversiKamera($kamera),
            5 => konversiStorage($storage),
        ];

        foreach ($nilai as $id_kriteria => $v) {
            mysqli_query($conn, "INSERT INTO nilai (id_smartphone, id_kriteria, nilai) 
            VALUES ('$id', '$id_kriteria', '$v')");
        }

        $specExist = mysqli_query($conn, "SELECT id FROM spesifikasi WHERE id_smartphone = $id LIMIT 1");
        if ($specExist && mysqli_num_rows($specExist) > 0) {
            mysqli_query($conn, "UPDATE spesifikasi SET harga = '$harga', ram = '$ram', benchmark_antutu = '$benchmark', kamera = '$kamera', storage = '$storage' WHERE id_smartphone = $id");
        } else {
            mysqli_query($conn, "INSERT INTO spesifikasi (id_smartphone, harga, ram, benchmark_antutu, kamera, storage) 
            VALUES ('$id', '$harga', '$ram', '$benchmark', '$kamera', '$storage')");
        }
    }

    header('Location: nilai.php');
    exit;
}

if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $harga = intval($_POST['harga']);
    $ram = intval($_POST['ram']);
    $benchmark = intval($_POST['benchmark_antutu']);
    $kamera = intval($_POST['kamera']);
    $storage = intval($_POST['storage']);

    if ($id > 0) {
        $nilai = [
            1 => konversiHarga($harga),
            2 => konversiRAM($ram),
            3 => konversiBenchmark($benchmark),
            4 => konversiKamera($kamera),
            5 => konversiStorage($storage),
        ];

        foreach ($nilai as $id_kriteria => $v) {
            mysqli_query($conn, "UPDATE nilai SET nilai = '$v' WHERE id_smartphone = $id AND id_kriteria = $id_kriteria");
        }

        mysqli_query($conn, "UPDATE spesifikasi SET harga = '$harga', ram = '$ram', benchmark_antutu = '$benchmark', kamera = '$kamera', storage = '$storage' WHERE id_smartphone = $id");
    }

    header('Location: nilai.php');
    exit;
}

$smartphone = mysqli_query($conn, "SELECT * FROM smartphone");
$nilaiList = mysqli_query($conn, "SELECT s.id, s.nama_smartphone, sp.harga, sp.ram, sp.benchmark_antutu, sp.kamera, sp.storage 
    FROM smartphone s 
    INNER JOIN spesifikasi sp ON s.id = sp.id_smartphone 
    ORDER BY s.nama_smartphone ASC");

$nilaiStats = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT id_smartphone) as total FROM spesifikasi"));
$smartphoneStats = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM smartphone"));

Require_once __DIR__ . '/../layout/sidebar.php';
?>

<div class="content">
    <div class="page-header">
        <div>
            <h2>📝 Input Nilai</h2>
            <p class="subtitle">Isi spesifikasi dan nilai setiap smartphone untuk mendapatkan peringkat SAW yang akurat.</p>
        </div>
    </div>

    <div class="card-grid">
        <div class="stat-card">
            <p class="eyebrow">Smartphone Terdaftar</p>
            <h3><?= $smartphoneStats['total'] ?></h3>
            <p>Jumlah smartphone yang tersedia untuk diinput nilainya.</p>
        </div>
        <div class="stat-card">
            <p class="eyebrow">Smartphone Dinilai</p>
            <h3><?= $nilaiStats['total'] ?></h3>
            <p>Jumlah smartphone yang sudah memiliki nilai & spesifikasi.</p>
        </div>
    </div>

    <div class="card">
        <form method="POST" class="form-box">
            <div class="form-group">
                <label for="smartphone">Pilih Smartphone</label>
                <select id="smartphone" class="input-field" name="smartphone" required <?= $editing ? 'disabled' : '' ?> >
                    <option value="">Pilih Smartphone</option>
                    <?php while ($s = mysqli_fetch_assoc($smartphone)) { ?>
                        <option value="<?= $s['id'] ?>" <?= ($selectedSmartphone == $s['id']) ? 'selected' : '' ?>><?= htmlspecialchars($s['nama_smartphone'], ENT_QUOTES, 'UTF-8') ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="harga">Harga</label>
                <input id="harga" class="input-field" type="number" name="harga" placeholder="Harga" value="<?= htmlspecialchars($inputHarga, ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <div class="form-group">
                <label for="ram">RAM</label>
                <input id="ram" class="input-field" type="number" name="ram" placeholder="RAM" value="<?= htmlspecialchars($inputRam, ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <div class="form-group">
                <label for="benchmark_antutu">AnTuTu 10</label>
                <input id="benchmark_antutu" class="input-field" type="number" name="benchmark_antutu" placeholder="AnTuTu 10" value="<?= htmlspecialchars($inputBenchmark, ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <div class="form-group">
                <label for="kamera">Kamera</label>
                <input id="kamera" class="input-field" type="number" name="kamera" placeholder="Kamera" value="<?= htmlspecialchars($inputKamera, ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <div class="form-group">
                <label for="storage">Storage</label>
                <input id="storage" class="input-field" type="number" name="storage" placeholder="Storage" value="<?= htmlspecialchars($inputStorage, ENT_QUOTES, 'UTF-8') ?>" required>
            </div>
            <div class="action-buttons">
                <?php if ($editing && $editSmartphone): ?>
                    <input type="hidden" name="id" value="<?= $editSmartphone['id_smartphone'] ?>">
                    <button class="btn btn-primary" type="submit" name="update">Perbarui</button>
                    <a href="nilai.php" class="btn btn-dark">Batal</a>
                <?php else: ?>
                    <button class="btn btn-primary" type="submit" name="simpan">Simpan</button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="table-responsive">
            <?php if (!$nilaiList || mysqli_num_rows($nilaiList) === 0): ?>
                <p class="no-data">Belum ada data nilai smartphone. Input data terlebih dahulu.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Smartphone</th>
                            <th>Harga</th>
                            <th>RAM</th>
                            <th>AnTuTu</th>
                            <th>Kamera</th>
                            <th>Storage</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($nilaiList)) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama_smartphone'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($row['harga'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($row['ram'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($row['benchmark_antutu'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($row['kamera'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($row['storage'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="nilai.php?edit=<?= $row['id'] ?>" class="btn btn-success btn-sm">Edit</a>
                                        <a href="nilai.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus nilai untuk <?= htmlspecialchars($row['nama_smartphone'], ENT_QUOTES, 'UTF-8') ?>?');">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>