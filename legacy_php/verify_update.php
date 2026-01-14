<?php
include 'koneksi.php';

try {
    $driver = $koneksi->getAttribute(PDO::ATTR_DRIVER_NAME);
    $rand_func = ($driver == 'pgsql') ? 'RANDOM()' : 'RAND()';

    $stmt = $koneksi->query("SELECT nama_mahasiswa, fakultas, foto FROM mahasiswa ORDER BY $rand_func LIMIT 5");
    echo "--- SAMPLE DATA MAHASISWA ---\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Nama: " . $row['nama_mahasiswa'] . "\n";
        echo "Fakultas: " . $row['fakultas'] . "\n";
        echo "Foto: " . substr($row['foto'] ?? '', 0, 50) . "...\n";
        echo "---------------------------\n";
    }

    $count_ba = $koneksi->query("SELECT COUNT(*) as c FROM mahasiswa WHERE fakultas='Blue Archive'")->fetch(PDO::FETCH_ASSOC)['c'];
    $count_tr = $koneksi->query("SELECT COUNT(*) as c FROM mahasiswa WHERE fakultas='Trickcal Revive'")->fetch(PDO::FETCH_ASSOC)['c'];
    $count_ss = $koneksi->query("SELECT COUNT(*) as c FROM mahasiswa WHERE fakultas='Stella Sora'")->fetch(PDO::FETCH_ASSOC)['c'];

    echo "Total Blue Archive: $count_ba\n";
    echo "Total Trickcal Revive: $count_tr\n";
    echo "Total Stella Sora: $count_ss\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
