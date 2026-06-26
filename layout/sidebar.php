<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(180deg, #eef2ff, #f8fafc);
    color: #1f2937;
}

/* SIDEBAR */
.sidebar {
    width: 250px;
    min-height: 100vh;
    position: fixed;
    background: #0f172a;
    padding: 32px 22px;
    color: white;
    box-shadow: 5px 0 36px rgba(15, 23, 42, 0.16);
}

.sidebar h2 {
    text-align: center;
    font-size: 24px;
    margin-bottom: 34px;
    letter-spacing: 0.04em;
}

.sidebar a {
    display: block;
    color: #cbd5e1;
    padding: 14px 22px;
    text-decoration: none;
    transition: 0.25s;
    font-size: 15px;
    border-radius: 14px;
    margin: 6px 12px;
}

.sidebar a:hover,
.sidebar a.active {
    background: rgba(255, 255, 255, 0.08);
    color: #ffffff;
    padding-left: 28px;
}

/* CONTENT */
.content {
    margin-left: 280px;
    padding: 36px 34px 60px;
    min-height: 100vh;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
    margin-bottom: 28px;
    flex-wrap: wrap;
}

.page-header h2 {
    margin: 0;
    font-size: 34px;
    letter-spacing: -0.03em;
}

.subtitle {
    margin: 12px 0 0;
    color: #475569;
    max-width: 720px;
    line-height: 1.75;
}

.card {
    background: white;
    padding: 26px;
    border-radius: 24px;
    box-shadow: 0 20px 44px rgba(15, 23, 42, 0.09);
    margin-bottom: 24px;
    border: 1px solid rgba(15, 23, 42, 0.05);
}

.card h3,
.card h2 {
    margin-top: 0;
    margin-bottom: 16px;
}

.card p {
    color: #475569;
    margin: 0;
}

.card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 20px;
    margin-bottom: 22px;
}

.stat-card {
    padding: 24px;
    border-radius: 24px;
    background: linear-gradient(180deg, #ffffff, #f8fafc);
    border: 1px solid rgba(59, 130, 246, 0.12);
}

.stat-card h3 {
    font-size: 42px;
    line-height: 1;
    margin: 12px 0 8px;
}

.stat-card p {
    margin: 0;
}

.eyebrow {
    margin: 0 0 10px;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    font-size: 12px;
    color: #64748b;
}

.hero-card {
    display: grid;
    gap: 20px;
    padding: 28px;
}

.hero-pill {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: #3b82f6;
    color: white;
    padding: 10px 16px;
    border-radius: 999px;
    font-weight: 700;
}

.form-box {
    display: grid;
    gap: 20px;
}

.form-group {
    display: grid;
    gap: 8px;
}

.form-group label {
    font-size: 14px;
    font-weight: 700;
    color: #334155;
}

.input-field,
select {
    width: 100%;
    padding: 14px 16px;
    border-radius: 14px;
    border: 1px solid #d1d5db;
    font-size: 15px;
    background: white;
    transition: border-color 0.2s, box-shadow 0.2s;
}

select {
    appearance: none;
}

.input-field:focus,
select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.14);
}

.btn {
    padding: 12px 18px;
    border: none;
    border-radius: 14px;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
}

.btn-sm {
    padding: 8px 12px;
    font-size: 13px;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 16px 30px rgba(59, 130, 246, 0.16);
}

.action-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    justify-content: flex-start;
}

.btn-primary { background: #3b82f6; color: white; }
.btn-success { background: #10b981; color: white; }
.btn-warning { background: #f59e0b; color: white; }
.btn-danger { background: #ef4444; color: white; }
.btn-dark { background: #0f172a; color: white; }

.table-responsive {
    overflow-x: auto;
}

table {
    width: 100%;
    min-width: 560px;
    border-collapse: collapse;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
}

th, td {
    padding: 18px 20px;
    text-align: left;
}

th {
    background: #111827;
    color: white;
    font-size: 14px;
    letter-spacing: 0.02em;
}

td {
    background: white;
    color: #334155;
    border-bottom: 1px solid #e2e8f0;
}

tbody tr:nth-child(even) td {
    background: #f8fafc;
}

tbody tr:hover td {
    background: #eef2ff;
}

.no-data {
    margin: 0;
    color: #64748b;
    padding: 18px;
    text-align: center;
}

@media (max-width: 960px) {
    .content {
        margin-left: 0;
        padding: 24px;
    }
    .sidebar {
        position: relative;
        width: 100%;
        min-height: auto;
        box-shadow: none;
        padding: 22px 18px;
    }
    .sidebar a {
        margin: 6px 0;
    }
}
</style>

<div class="sidebar">
    <h2>📱 SPK SAW</h2>
    <a href="index.php" class="<?= $currentPage === 'index.php' ? 'active' : '' ?>">🏠 Dashboard</a>
    <a href="smartphone.php" class="<?= $currentPage === 'smartphone.php' ? 'active' : '' ?>">📱 Smartphone</a>
    <a href="kriteria.php" class="<?= $currentPage === 'kriteria.php' ? 'active' : '' ?>">📊 Kriteria</a>
    <a href="nilai.php" class="<?= $currentPage === 'nilai.php' ? 'active' : '' ?>">📝 Input Nilai</a>
    <a href="hasil.php" class="<?= $currentPage === 'hasil.php' ? 'active' : '' ?>">🏆 Hasil Ranking</a>
    <!-- <a href="../proses/hitung_saw.php" class="<?= $currentPage === 'hitung_saw.php' ? 'active' : '' ?>">⚙️ Hitung SAW</a> -->
</div>