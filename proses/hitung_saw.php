<?php
require_once __DIR__ . '/../config/koneksi.php';

// ================== AMBIL DATA ==================
$qSmartphone = mysqli_query($conn, "SELECT * FROM smartphone");
$qKriteria   = mysqli_query($conn, "SELECT * FROM kriteria");

// simpan kriteria ke array
$kriteria = [];
while ($k = mysqli_fetch_assoc($qKriteria)) {
    $kriteria[$k['id']] = $k;
}

// simpan data smartphone
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
    if (isset($data[$n['id_smartphone']])) {
        $data[$n['id_smartphone']]['nilai'][$n['id_kriteria']] = $n['nilai'];
    }
}

// ================== NORMALISASI ==================
$normalisasi = [];

foreach ($kriteria as $id_k => $k) {

    $max = 0;
    $min = PHP_INT_MAX;

    // cari max & min
    foreach ($data as $d) {
        if (!isset($d['nilai'][$id_k])) continue;

        $nilai = $d['nilai'][$id_k];

        if ($nilai > $max) $max = $nilai;
        if ($nilai < $min) $min = $nilai;
    }

    // normalisasi
    foreach ($data as $id_s => $d) {
        if (!isset($d['nilai'][$id_k])) continue;

        $nilai = $d['nilai'][$id_k];

        if ($k['tipe'] == 'benefit') {
            $normalisasi[$id_s][$id_k] = $nilai / ($max ?: 1);
        } else { // cost
            $normalisasi[$id_s][$id_k] = ($min ?: 1) / ($nilai ?: 1);
        }
    }
}

// ================== HITUNG NILAI AKHIR ==================
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

// ================== SORTING RANKING ==================
usort($hasil, function($a, $b) {
    return $b['nilai'] <=> $a['nilai'];
});

// ================== OUTPUT ==================
?>
<!DOCTYPE html>
<html>
<head>
    <title>Hasil SPK SAW - Smartphone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 20px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #4CAF50;
            color: white;
        }

        tr:hover {
            background: #f1f1f1;
        }

        .rank1 {
            background: #d4edda;
            font-weight: bold;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 5px;
            color: white;
            font-size: 12px;
        }

        .gold { background: #f1c40f; }
        .silver { background: #bdc3c7; }
        .bronze { background: #cd7f32; }
    </style>
</head>
<body>

<div class="container">
    <h2>🏆 Hasil Ranking Smartphone (Metode SAW)</h2>
    <hr>

    <table>
        <tr>
            <th>Rank</th>
            <th>Smartphone</th>
            <th>Nilai</th>
            <th>Keterangan</th>
        </tr>

        <?php
        $rank = 1;
        foreach ($hasil as $h) {

            $class = "";
            $badge = "";

            if ($rank == 1) {
                $class = "rank1";
                $badge = "<span class='badge gold'>Juara 1</span>";
            } elseif ($rank == 2) {
                $badge = "<span class='badge silver'>Juara 2</span>";
            } elseif ($rank == 3) {
                $badge = "<span class='badge bronze'>Juara 3</span>";
            }

            echo "<tr class='$class'>
                    <td>#$rank</td>
                    <td>{$h['nama']}</td>
                    <td>" . round($h['nilai'], 3) . "</td>
                    <td>$badge</td>
                  </tr>";

            $rank++;
        }
        ?>

    </table>
</div>

</body>
</html>