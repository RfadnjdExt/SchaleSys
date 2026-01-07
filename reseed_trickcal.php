<?php
// reseed_trickcal.php
// Script untuk merombak database, set jumlah data sesuai karakter asli (random order), dan tambah kolom fakultas.
// VERSI FIXED: Menggunakan Array PHP langsung.

if (php_sapi_name() !== 'cli') {
    die("Script ini hanya boleh dijalankan melalui CLI.");
}

include 'koneksi.php';

// Data Array PHP (Name => URL)
$data_karakter = [
  "Amelia" => "https://lootandwaifus.com/characters/trickcal/amelia.webp",
  "Asia" => "https://lootandwaifus.com/characters/trickcal/asia.webp",
  "Aya" => "https://lootandwaifus.com/characters/trickcal/aya.webp",
  "Barong" => "https://lootandwaifus.com/characters/trickcal/barong.webp",
  "Benny" => "https://lootandwaifus.com/characters/trickcal/benny.webp",
  "Caesar" => "https://lootandwaifus.com/characters/trickcal/caesar.webp",
  "Canta" => "https://lootandwaifus.com/characters/trickcal/canta.webp",
  "Diana (Heyday)" => "https://lootandwaifus.com/characters/trickcal/diana-heyday.webp",
  "Ed" => "https://lootandwaifus.com/characters/trickcal/ed.webp",
  "Elena" => "https://lootandwaifus.com/characters/trickcal/elena.webp",
  "Espi" => "https://lootandwaifus.com/characters/trickcal/espi.webp",
  "Fricle" => "https://lootandwaifus.com/characters/trickcal/fricle.webp",
  "Gwynn" => "https://lootandwaifus.com/characters/trickcal/gwynn.webp",
  "Jade" => "https://lootandwaifus.com/characters/trickcal/jade.webp",
  "Kommy (Swimsuit)" => "https://lootandwaifus.com/characters/trickcal/kommy-swimsuit.webp",
  "Layze" => "https://lootandwaifus.com/characters/trickcal/layze.webp",
  "Lethe" => "https://lootandwaifus.com/characters/trickcal/lethe.webp",
  "Meluna" => "https://lootandwaifus.com/characters/trickcal/meluna.webp",
  "Patula" => "https://lootandwaifus.com/characters/trickcal/patula.webp",
  "Picora" => "https://lootandwaifus.com/characters/trickcal/picora.webp",
  "Ricotta" => "https://lootandwaifus.com/characters/trickcal/ricotta.webp",
  "Sylla" => "https://lootandwaifus.com/characters/trickcal/sylla.webp",
  "Velvet" => "https://lootandwaifus.com/characters/trickcal/velvet.webp",
  "Asana" => "https://lootandwaifus.com/characters/trickcal/asana.webp",
  "Ashur" => "https://lootandwaifus.com/characters/trickcal/ashur.webp",
  "Barie" => "https://lootandwaifus.com/characters/trickcal/barie.webp",
  "Blanchet" => "https://lootandwaifus.com/characters/trickcal/blanchet.webp",
  "Chopi" => "https://lootandwaifus.com/characters/trickcal/chopi.webp",
  "Festa" => "https://lootandwaifus.com/characters/trickcal/festa.webp",
  "Hilde" => "https://lootandwaifus.com/characters/trickcal/hilde.webp",
  "Joan" => "https://lootandwaifus.com/characters/trickcal/joan.webp",
  "Kidian" => "https://lootandwaifus.com/characters/trickcal/kidian.webp",
  "Kommy" => "https://lootandwaifus.com/characters/trickcal/kommy.webp",
  "Leone" => "https://lootandwaifus.com/characters/trickcal/leone.webp",
  "Levi" => "https://lootandwaifus.com/characters/trickcal/levi.webp",
  "Orr" => "https://lootandwaifus.com/characters/trickcal/orr.webp",
  "Posher" => "https://lootandwaifus.com/characters/trickcal/posher.webp",
  "Rim" => "https://lootandwaifus.com/characters/trickcal/rim.webp",
  "Risty" => "https://lootandwaifus.com/characters/trickcal/risty.webp",
  "Rohne (Mayor)" => "https://lootandwaifus.com/characters/trickcal/rohne-mayor.webp",
  "Shasha" => "https://lootandwaifus.com/characters/trickcal/shasha.webp",
  "Snorky" => "https://lootandwaifus.com/characters/trickcal/snorky.webp",
  "Veroo" => "https://lootandwaifus.com/characters/trickcal/veroo.webp",
  "xXionx" => "https://lootandwaifus.com/characters/trickcal/xion.webp",
  "Yomi" => "https://lootandwaifus.com/characters/trickcal/yomi.webp",
  "Allet" => "https://lootandwaifus.com/characters/trickcal/allet.webp",
  "Ayla" => "https://lootandwaifus.com/characters/trickcal/ayla.webp",
  "Bigwood" => "https://lootandwaifus.com/characters/trickcal/bigwood.webp",
  "Daya" => "https://lootandwaifus.com/characters/trickcal/daya.webp",
  "Erpin" => "https://lootandwaifus.com/characters/trickcal/erpin.webp",
  "Gabia" => "https://lootandwaifus.com/characters/trickcal/gabia.webp",
  "Haley" => "https://lootandwaifus.com/characters/trickcal/haley.webp",
  "Kathy" => "https://lootandwaifus.com/characters/trickcal/kathy.webp",
  "Kyarot" => "https://lootandwaifus.com/characters/trickcal/kyarot.webp",
  "Kyuri" => "https://lootandwaifus.com/characters/trickcal/kyuri.webp",
  "Mago" => "https://lootandwaifus.com/characters/trickcal/mago.webp",
  "Mayo (Cool)" => "https://lootandwaifus.com/characters/trickcal/mayo-cool.webp",
  "Mute" => "https://lootandwaifus.com/characters/trickcal/mute.webp",
  "Naia" => "https://lootandwaifus.com/characters/trickcal/naia.webp",
  "Opal" => "https://lootandwaifus.com/characters/trickcal/opal.webp",
  "Raika" => "https://lootandwaifus.com/characters/trickcal/laika.webp",
  "Ran" => "https://lootandwaifus.com/characters/trickcal/ran.webp",
  "Rohne" => "https://lootandwaifus.com/characters/trickcal/rohne.webp",
  "Sari" => "https://lootandwaifus.com/characters/trickcal/sari.webp",
  "Sherum" => "https://lootandwaifus.com/characters/trickcal/sherum.webp",
  "Silphir" => "https://lootandwaifus.com/characters/trickcal/silphir.webp",
  "Speaki" => "https://lootandwaifus.com/characters/trickcal/speaki.webp",
  "Vivi" => "https://lootandwaifus.com/characters/trickcal/vivi.webp",
  "Alice" => "https://lootandwaifus.com/characters/trickcal/alice.webp",
  "Annette" => "https://lootandwaifus.com/characters/trickcal/annette.webp",
  "Belita" => "https://lootandwaifus.com/characters/trickcal/belita.webp",
  "Chloe" => "https://lootandwaifus.com/characters/trickcal/chloe.webp",
  "Diana" => "https://lootandwaifus.com/characters/trickcal/diana.webp",
  "Heidi" => "https://lootandwaifus.com/characters/trickcal/heidi.webp",
  "Ifrit" => "https://lootandwaifus.com/characters/trickcal/ifrit.webp",
  "Leets" => "https://lootandwaifus.com/characters/trickcal/leets.webp",
  "MaestroMK2" => "https://lootandwaifus.com/characters/trickcal/maestro.webp",
  "Maison" => "https://lootandwaifus.com/characters/trickcal/maison.webp",
  "Mayo" => "https://lootandwaifus.com/characters/trickcal/mayo.webp",
  "Ner" => "https://lootandwaifus.com/characters/trickcal/ner.webp",
  "Neti" => "https://lootandwaifus.com/characters/trickcal/neti.webp",
  "Pira" => "https://lootandwaifus.com/characters/trickcal/pira.webp",
  "Polan" => "https://lootandwaifus.com/characters/trickcal/polan.webp",
  "Renewa" => "https://lootandwaifus.com/characters/trickcal/renewa.webp",
  "Rim (Chaos)" => "https://lootandwaifus.com/characters/trickcal/rim-chaos.webp",
  "Rollet" => "https://lootandwaifus.com/characters/trickcal/rollet.webp",
  "Shaydi" => "https://lootandwaifus.com/characters/trickcal/shady.webp",
  "Sist" => "https://lootandwaifus.com/characters/trickcal/sist.webp",
  "Tig (Hero)" => "https://lootandwaifus.com/characters/trickcal/tig-hero.webp",
  "Yumimi" => "https://lootandwaifus.com/characters/trickcal/yumimi.webp",
  "Arco" => "https://lootandwaifus.com/characters/trickcal/arco.webp",
  "Bana" => "https://lootandwaifus.com/characters/trickcal/bana.webp",
  "Beni" => "https://lootandwaifus.com/characters/trickcal/beni.webp",
  "Butter" => "https://lootandwaifus.com/characters/trickcal/butter.webp",
  "Canna" => "https://lootandwaifus.com/characters/trickcal/canna.webp",
  "Carren" => "https://lootandwaifus.com/characters/trickcal/carren.webp",
  "Epica" => "https://lootandwaifus.com/characters/trickcal/epica.webp",
  "Jubee" => "https://lootandwaifus.com/characters/trickcal/jubee.webp",
  "Makasha" => "https://lootandwaifus.com/characters/trickcal/makasha.webp",
  "Marie" => "https://lootandwaifus.com/characters/trickcal/marie.webp",
  "Miro" => "https://lootandwaifus.com/characters/trickcal/miro.webp",
  "Momo" => "https://lootandwaifus.com/characters/trickcal/momo.webp",
  "Mynx" => "https://lootandwaifus.com/characters/trickcal/mynx.webp",
  "Rudd" => "https://lootandwaifus.com/characters/trickcal/rude.webp",
  "Rufo" => "https://lootandwaifus.com/characters/trickcal/rufo.webp",
  "Selline" => "https://lootandwaifus.com/characters/trickcal/selinne.webp",
  "Shoupan" => "https://lootandwaifus.com/characters/trickcal/shoupan.webp",
  "Shuro" => "https://lootandwaifus.com/characters/trickcal/suro.webp",
  "Speaki (Maid)" => "https://lootandwaifus.com/characters/trickcal/speaki-maid.webp",
  "Taida" => "https://lootandwaifus.com/characters/trickcal/taida.webp",
  "Tig" => "https://lootandwaifus.com/characters/trickcal/tig.webp",
  "Ui" => "https://lootandwaifus.com/characters/trickcal/ui.webp",
  "Vela" => "https://lootandwaifus.com/characters/trickcal/vela.webp",
  "Uros" => "https://lootandwaifus.com/characters/trickcal/uros.webp"
];

$keys = array_keys($data_karakter);
shuffle($keys); // ACAK Urutan

// 1. Tambah Kolom 'fakultas' jika belum ada
$check_col = mysqli_query($koneksi, "SHOW COLUMNS FROM mahasiswa LIKE 'fakultas'");
if (mysqli_num_rows($check_col) == 0) {
    mysqli_query($koneksi, "ALTER TABLE mahasiswa ADD COLUMN fakultas VARCHAR(100) AFTER nama_mahasiswa");
    echo "Kolom 'fakultas' berhasil ditambahkan.\n";
}

// 2. Kosongkan Tabel (Truncate)
// Matikan Foreign Key Check di awal dan hidupkan kembali di akhir untuk keamanan
mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS = 0");
if (!mysqli_query($koneksi, "TRUNCATE TABLE mahasiswa")) {
    die("Gagal truncate: " . mysqli_error($koneksi));
}
mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS = 1");
echo "Tabel mahasiswa dikosongkan.\n";

// 3. Insert Ulang dengan Urutan Acak
echo "Mulai seeding ulang (" . count($keys) . " karakter)...\n";

$nim_start = 2024001;
$angkatan = 2024;
$fakultas_default = "Trickcal Revive"; // Nama Game

$query = "INSERT INTO mahasiswa (nim, nama_mahasiswa, fakultas, prodi, angkatan, foto) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($koneksi, $query);

if (!$stmt) {
    die("Prepare failed: " . mysqli_error($koneksi));
}

foreach ($keys as $index => $nama) {
    $nim = $nim_start + $index;
    $url = $data_karakter[$nama];
    $prodi = "Petualang"; 

    mysqli_stmt_bind_param($stmt, "ssssis", $nim, $nama, $fakultas_default, $prodi, $angkatan, $url);
    if (!mysqli_stmt_execute($stmt)) {
        echo "Gagal insert $nama: " . mysqli_stmt_error($stmt) . "\n";
    }
}

echo "Sukses! Database telah dirombak dengan " . count($keys) . " karakter Trickcal.\n";
mysqli_close($koneksi);
?>
