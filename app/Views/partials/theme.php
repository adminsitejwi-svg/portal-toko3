<?php
/**
 * Tema tampilan RuangAdmin + dark mode untuk semua halaman.
 * Dipanggil tepat sebelum </head> dengan: <?= view('partials/theme') ?>
 *
 * Versi file (filemtime) ditambahkan agar browser memuat ulang saat tema diubah.
 */
$ver = static fn (string $file) => @filemtime(FCPATH . $file) ?: '1';
?>
    <!-- ===== Tema RuangAdmin + dark mode (public/assets/theme) ===== -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('assets/theme/ruang.css') ?>?v=<?= $ver('assets/theme/ruang.css') ?>">
    <script src="<?= base_url('assets/theme/ruang.js') ?>?v=<?= $ver('assets/theme/ruang.js') ?>"></script>
