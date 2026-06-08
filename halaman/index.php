<?php require_once __DIR__ . '/../config/koneksi.php'; ?>
<?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

<?php
$hp = mysqli_query($conn, "SELECT COUNT(*) as total FROM smartphone");
$dataSmartphone = mysqli_fetch_assoc($hp);
$hk = mysqli_query($conn, "SELECT COUNT(*) as total FROM kriteria");
$dataKriteria = mysqli_fetch_assoc($hk);
$hn = mysqli_query($conn, "SELECT COUNT(*) as total FROM nilai");
$dataNilai = mysqli_fetch_assoc($hn);
?>

<div class="content">
    <div class="page-header">
        <div>
            <h2>📊 Dashboard SPK SAW</h2>
            <p class="subtitle">Pantau kondisi data sistem dan gunakan shortcut cepat untuk mengelola smartphone, kriteria, nilai, serta melihat hasil ranking.</p>
        </div>
    </div>

    <div class="card-grid">
        <div class="stat-card">
            <p class="eyebrow">Smartphone Terdaftar</p>
            <h3><?= $dataSmartphone['total'] ?></h3>
            <p>Jumlah smartphone yang siap dinilai oleh sistem.</p>
        </div>
        <div class="stat-card">
            <p class="eyebrow">Kriteria Aktif</p>
            <h3><?= $dataKriteria['total'] ?></h3>
            <p>Bobot dan tipe kriteria untuk proses perhitungan SAW.</p>
        </div>
        <div class="stat-card">
            <p class="eyebrow">Nilai Tersimpan</p>
            <h3><?= $dataNilai['total'] ?></h3>
            <p>Data nilai yang telah diinput untuk smartphone.</p>
        </div>
    </div>

    <div class="card">
        <h3>Mulai Mengelola Data</h3>
        <p class="subtitle">Akses halaman penting untuk menambah smartphone, mengatur kriteria, mengisi nilai, dan melihat rekomendasi ranking secara langsung.</p>
        <div class="action-buttons">
            <a href="smartphone.php" class="btn btn-primary">Smartphone</a>
            <a href="kriteria.php" class="btn btn-success">Kriteria</a>
            <a href="nilai.php" class="btn btn-warning">Input Nilai</a>
        </div>
    </div>
</div>