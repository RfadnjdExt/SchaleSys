<?php
include 'koneksi.php';

try {
    $driver = $koneksi->getAttribute(PDO::ATTR_DRIVER_NAME);

    // Array kolom yang akan ditambahkan default MySQL
    $columns = [
        'hari' => "VARCHAR(10) NULL", // COMMENT not supported inline in Postgres, simplifying for compat
        'jam_mulai' => "TIME NULL",
        'jam_selesai' => "TIME NULL",
        'ruangan' => "VARCHAR(20) NULL"
    ];

    $success_count = 0;

    foreach ($columns as $col_name => $col_definition) {
        $exists = false;

        if ($driver == 'pgsql') {
            // Cek kolom di Postgres
            $stmt = $koneksi->prepare("SELECT column_name FROM information_schema.columns WHERE table_name = 'mata_kuliah' AND column_name = ?");
            $stmt->execute([$col_name]);
            if ($stmt->fetch()) {
                $exists = true;
            }
        } else {
            // Cek kolom di MySQL
            $stmt = $koneksi->query("SHOW COLUMNS FROM mata_kuliah LIKE '$col_name'");
            if ($stmt->fetch()) {
                $exists = true;
            }
        }
        
        if (!$exists) {
            // Jika belum ada, lakukan ALTER TABLE
            // Definisi kolom mungkin perlu penyesuaian untuk Postgres tapi tipe dasar di atas kompatibel
            $alter_sql = "ALTER TABLE mata_kuliah ADD COLUMN $col_name $col_definition";
            
            // Tambahkan COMMENT untuk MySQL secara spesifik jika diinginkan, tapi untuk kesederhanaan kita skip atau pisah
            if ($driver == 'mysql' && $col_name == 'hari') {
                 $alter_sql .= " COMMENT 'Senin, Selasa, dst'";
            }

            $koneksi->exec($alter_sql);
            echo "Kolom '$col_name' berhasil ditambahkan.<br>";
            $success_count++;
        } else {
            echo "Kolom '$col_name' sudah ada, melewati...<br>";
        }
    }

    if ($success_count > 0) {
        echo "<b>Selesai! Struktur tabel mata_kuliah telah diperbarui.</b>";
    } else {
        echo "<b>Tidak ada perubahan yang dilakukan (mungkin kolom sudah ada semua).</b>";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
