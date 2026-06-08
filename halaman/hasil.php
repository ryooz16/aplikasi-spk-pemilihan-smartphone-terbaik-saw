<?php
require_once __DIR__ . '/../config/koneksi.php';

// ================== AMBIL DATA ==================
$qSmartphone = mysqli_query($conn, "SELECT * FROM smartphone");
$qKriteria   = mysqli_query($conn, "SELECT * FROM kriteria");

// simpan kriteria
$kriteria = [];
while ($k = mysqli_fetch_assoc($qKriteria)) {
    $kriteria[$k['id']] = $k;
}

// simpan smartphone
$data = [];
while ($s = mysqli_fetch_assoc($qSmartphone)) {
    $data[$s['id']] = [
        'nama'  => $s['nama_smartphone'],
        'nilai' => []
    ];
}

// ambil nilai
$qNilai = mysqli_query($conn, "SELECT * FROM nilai");
while ($n = mysqli_fetch_assoc($qNilai)) {
    $data[$n['id_smartphone']]['nilai'][$n['id_kriteria']] = $n['nilai'];
}

// ambil spesifikasi
$spesifikasi = [];
$qSpek = mysqli_query($conn, "SELECT * FROM spesifikasi");
while ($s = mysqli_fetch_assoc($qSpek)) {
    $spesifikasi[$s['id_smartphone']] = $s;
}

// ================== NORMALISASI ==================
$normalisasi = [];
foreach ($kriteria as $id_k => $k) {
    $max = 0;
    $min = PHP_INT_MAX;

    foreach ($data as $d) {
        if (!isset($d['nilai'][$id_k])) continue;
        $nilai = $d['nilai'][$id_k];
        if ($nilai > $max) $max = $nilai;
        if ($nilai < $min) $min = $nilai;
    }

    foreach ($data as $id_s => $d) {
        if (!isset($d['nilai'][$id_k])) continue;
        $nilai = $d['nilai'][$id_k];
        if ($k['tipe'] == 'benefit') {
            $normalisasi[$id_s][$id_k] = $nilai / ($max ?: 1);
        } else {
            $normalisasi[$id_s][$id_k] = ($min ?: 1) / ($nilai ?: 1);
        }
    }
}

// ================== HITUNG SAW ==================
$hasil = [];
foreach ($data as $id_s => $d) {
    $total = 0;
    foreach ($kriteria as $id_k => $k) {
        if (!isset($normalisasi[$id_s][$id_k])) continue;
        $total += $normalisasi[$id_s][$id_k] * $k['bobot'];
    }
    $hasil[] = [
        'id'    => $id_s,
        'nama'  => $d['nama'],
        'nilai' => $total
    ];
}

usort($hasil, function($a, $b) {
    return $b['nilai'] <=> $a['nilai'];
});

Require_once __DIR__ . '/../layout/sidebar.php';
?>

<div class="content">
    <div class="page-header">
        <div>
            <h2>🏆 Hasil Ranking</h2>
            <p class="subtitle">Lihat peringkat smartphone terbaik berdasarkan perhitungan metode SAW. Halaman ini membantu memilih smartphone unggulan dari data yang sudah diinput.</p>
        </div>
        <a href="index.php" class="btn btn-dark">Kembali</a>
    </div>

    <?php if (!empty($hasil)) :
        $top = $hasil[0];
        $bestSpek = $spesifikasi[$top['id']] ?? null;
    ?>
        <div class="card hero-card">
            <span class="hero-pill">🥇 Top Recommended</span>
            <div>
                <h3><?= htmlspecialchars($top['nama'], ENT_QUOTES, 'UTF-8') ?></h3>
                <p class="subtitle">Smartphone terbaik berdasarkan perhitungan nilai SAW.</p>
            </div>
            <div class="card-grid">
                <div class="stat-card">
                    <p class="eyebrow">Nilai SAW</p>
                    <h3><?= round($top['nilai'], 3) ?></h3>
                </div>
                <?php if ($bestSpek): ?>
                    <div class="stat-card">
                        <p class="eyebrow">Harga</p>
                        <h3>Rp <?= number_format($bestSpek['harga']) ?></h3>
                    </div>
                    <div class="stat-card">
                        <p class="eyebrow">RAM</p>
                        <h3><?= $bestSpek['ram'] ?> GB</h3>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="card">
        <h3>Ranking Lengkap</h3>
        <div class="table-responsive">
            <?php if (empty($hasil)) : ?>
                <p class="no-data">Belum ada hasil ranking karena data smartphone atau nilai belum lengkap.</p>
            <?php else : ?>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 72px;">Rank</th>
                            <th>Smartphone</th>
                            <th style="width: 140px;">Nilai SAW</th>
                            <th style="width: 120px;">Harga</th>
                            <th style="width: 100px;">RAM</th>
                            <th style="width: 120px;">AnTuTu</th>
                            <th style="width: 110px;">Kamera</th>
                            <th style="width: 110px;">Storage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $rank = 1; foreach ($hasil as $h) :
                            $spek = $spesifikasi[$h['id']] ?? null;
                        ?>
                            <tr>
                                <td><?= $rank++ ?></td>
                                <td><?= htmlspecialchars($h['nama'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= round($h['nilai'], 3) ?></td>
                                <td><?= $spek ? 'Rp ' . number_format($spek['harga']) : '-' ?></td>
                                <td><?= $spek ? $spek['ram'] . ' GB' : '-' ?></td>
                                <td><?= $spek ? $spek['benchmark_antutu'] : '-' ?></td>
                                <td><?= $spek ? $spek['kamera'] . ' MP' : '-' ?></td>
                                <td><?= $spek ? $spek['storage'] . ' GB' : '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
