<?php

use App\Models\SuratTugasModel;

// Kelas logo per perusahaan (ukuran potong logo sama dengan kop RFO)
$kop = [
    'PT JURAGAN WIFI INDONESIA'          => 'jwi',
    'PT GENEZIS MITRA TECHNOLOGY'        => 'flazz',
    'PT NORLEC TELEKOMUNIKASI INDONESIA' => 'norlec',
    'PT RTIGA GLOBAL MEDIA'              => 'rtiga',
][$st['perusahaan']] ?? 'jwi';

$baris = [
    'Hari'      => SuratTugasModel::hariTugas($st),
    'Tanggal'   => SuratTugasModel::tanggalTugas($st),
    'Keperluan' => $st['keperluan'],
    'Teknisi'   => SuratTugasModel::gabungNama($teknisi),
    'Lokasi'    => $st['lokasi'],
];
?>
<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title>Surat Tugas <?= esc($st['nomor']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?= view('SuratTugas/_head') ?>
    <style>
        /* ===== Surat Tugas (tata letak mengikuti 17 SPK NCIX KARET_OCT.pdf) ===== */
        .st-toolbar {
            max-width: 794px;
            margin: 0 auto 16px;
        }

        .st-paper {
            position: relative;
            max-width: 794px;
            min-height: 1123px;
            margin: 0 auto;
            padding: 46px 82px 150px 96px;
            background: #fff;
            box-shadow: 0 1px 6px rgba(0, 0, 0, .15);
            color: #000;
            font-family: "Times New Roman", Tinos, Times, serif;
            font-size: 16px;
            line-height: 1.3;
        }

        /* Logo kiri atas; file logo punya banyak ruang kosong, bingkai memotong sisa ruang */
        .st-logo {
            margin-left: -88px;
            overflow: hidden;
        }

        /* .st-crop = bingkai potong logo, dipakai kop (kiri atas) & stempel (tanda tangan) */
        .st-crop {
            overflow: hidden;
        }

        .st-crop img {
            max-width: none;
        }

        .st-paper--jwi .st-crop { width: 260px; height: 52px; }
        .st-paper--jwi .st-crop img { width: 280px; margin: -47px 0 0 -6px; }
        .st-paper--flazz .st-crop { width: 205px; height: 41px; }
        .st-paper--flazz .st-crop img { width: 232px; margin: -38px 0 0 -14px; }
        .st-paper--norlec .st-crop { width: 240px; height: 40px; }
        .st-paper--norlec .st-crop img { width: 271px; margin: -17px 0 0 -11px; }
        .st-paper--rtiga .st-crop { width: 190px; height: 58px; }
        .st-paper--rtiga .st-crop img { width: 202px; margin: -8px 0 0 -6px; }

        .st-date {
            margin-top: 32px;
            text-align: right;
        }

        .st-title {
            margin-top: 40px;
            text-align: center;
            font-weight: 700;
            line-height: 1.35;
        }

        .st-title u {
            text-underline-offset: 2px;
        }

        .st-par {
            margin-top: 22px;
            text-align: justify;
            line-height: 2;
        }

        .st-detail {
            margin: 48px 0 0 48px;
            border-collapse: collapse;
        }

        .st-detail td {
            padding: 2px 0 7px;
            vertical-align: top;
        }

        .st-detail td:first-child {
            width: 100px;
        }

        .st-detail td.st-colon {
            padding-right: 4px;
        }

        .st-alat {
            margin: 26px 0 0 118px;
            width: calc(100% - 118px);
            border-collapse: collapse;
            font-size: 14px;
        }

        .st-alat th,
        .st-alat td {
            border: 1px solid #000;
            padding: 0 6px;
            text-align: center;
            font-weight: 400;
            height: 19px;
        }

        .st-alat th {
            font-size: 15px;
            text-align: left;
        }

        .st-alat th:first-child,
        .st-alat td:first-child {
            width: 42px;
            text-align: left;
        }

        .st-penutup {
            margin-top: 52px;
            line-height: 1.25;
        }

        .st-sign {
            margin: 46px 0 0 338px;
        }

        /* Ruang tanda tangan & stempel */
        .st-sign-ttd {
            position: relative;
            height: 130px;
            display: flex;
            align-items: center;
        }

        /* Stempel logo perusahaan di bawah tanda tangan, sedikit miring seperti cap */
        .st-stamp {
            position: absolute;
            left: -75px;
            top: 50%;
            transform: translateY(-50%) rotate(-8deg);
            opacity: .85;
            z-index: 1;
        }

        .st-sign-ttd img.st-ttd {
            position: relative;
            z-index: 2;
            max-height: 130px;
            max-width: 300px;
            object-fit: contain;
            margin-left: -50px;
        }

        .st-sign-nama {
            display: inline-block;
            min-width: 170px;
            text-decoration: underline;
            text-underline-offset: 2px;
        }

        .st-sign-jabatan {
            margin-top: 8px;
        }

        .st-foot {
            position: absolute;
            left: 96px;
            bottom: 56px;
            font-family: Calibri, Carlito, "Segoe UI", Arial, sans-serif;
            font-size: 15px;
            line-height: 1.4;
        }

        @media (max-width: 860px) {
            .st-paper {
                padding: 32px 24px 150px;
            }

            .st-logo {
                margin-left: -8px;
            }

            .st-detail {
                margin-left: 0;
            }

            .st-alat {
                margin-left: 0;
                width: 100%;
            }

            .st-sign {
                margin-left: auto;
                width: fit-content;
            }

            .st-foot {
                left: 24px;
            }
        }

        /* ===== PRINT: hanya kertas surat yang dicetak (A4) ===== */
        @page {
            size: A4;
            margin: 0;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .st-paper,
            .st-paper * {
                visibility: visible;
            }

            html,
            body {
                background: #fff !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .st-paper {
                position: absolute;
                left: 0;
                top: 0;
                width: 210mm;
                min-height: 297mm;
                max-width: none;
                margin: 0;
                padding: 46px 82px 150px 96px;
                box-shadow: none;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .st-logo { margin-left: -88px; }
            .st-detail { margin-left: 48px; }
            .st-alat { margin-left: 118px; width: calc(100% - 118px); }
            .st-sign { margin-left: 338px; width: auto; }
            .st-foot { left: 96px; }
        }
    </style>
</head>

<body class="text-[#37474f] dark:text-[#bfc8d6]">
    <?= view('SuratTugas/_sidebar') ?>

        <div class="p-6">
            <!-- TOOLBAR (tidak ikut tercetak) -->
            <div class="st-toolbar flex flex-wrap items-center justify-between gap-3">
                <h1 class="rfo-title whitespace-nowrap">View Surat Tugas</h1>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="<?= site_url('SuratTugas') ?>" class="rfo-btn rfo-btn-gray"><i class="ti ti-arrow-left"></i> Kembali</a>
                    <a href="<?= site_url('SuratTugas/edit/' . $st['id']) ?>" class="rfo-btn rfo-btn-amber"><i class="ti ti-edit"></i> Edit</a>
                    <a href="#" role="button" onclick="window.print(); return false;" class="rfo-btn rfo-btn-blue"><i class="ti ti-printer"></i> Print</a>
                </div>
            </div>

            <!-- KERTAS SURAT TUGAS -->
            <div class="st-paper st-paper--<?= $kop ?>">
                <?php if (!empty($st['logo'])) : ?>
                    <div class="st-logo st-crop"><img src="<?= base_url($st['logo']) ?>" alt="Logo <?= esc($st['perusahaan']) ?>"></div>
                <?php endif; ?>

                <div class="st-date"><?= esc(SuratTugasModel::tanggalIndo($st['tanggal_surat'])) ?></div>

                <div class="st-title">
                    <u>SURAT TUGAS</u><br>
                    <?= esc($st['nomor']) ?>
                </div>

                <div class="st-par">
                    Bersama ini kami dari <?= esc(str_replace('PT. ', 'PT ', $namaPt)) ?><?= !empty($st['mitra']) ? ' selaku Client colocation ' . esc($st['mitra']) . '.' : '' ?>
                    menugaskan nama-nama dibawah ini untuk melaksanakan kegiatan <?= esc($st['kegiatan']) ?>. Dengan rincian sebagai berikut :
                </div>

                <table class="st-detail">
                    <?php foreach ($baris as $label => $isi) : ?>
                        <tr>
                            <td><?= $label ?></td>
                            <td class="st-colon">:</td>
                            <td><?= esc($isi) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>

                <?php if (!empty($perangkat)) : ?>
                    <table class="st-alat">
                        <tr>
                            <th>NO</th>
                            <th>Merk Perangkat</th>
                            <th>Jenis Perangkat</th>
                            <th>Serial Number</th>
                        </tr>
                        <?php foreach ($perangkat as $i => $p) : ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= esc($p['merk'] ?? '') ?></td>
                                <td><?= esc($p['jenis'] ?? '') ?></td>
                                <td><?= esc($p['sn'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>

                <div class="st-penutup">
                    Demikian surat perintah kerja ini kami buat agar dapat dipergunakan untuk
                    <?= esc($st['penutup'] ?: 'keperluan perizinan') ?>
                </div>

                <div class="st-sign">
                    <div><?= esc($namaPt) ?></div>
                    <div class="st-sign-ttd">
                        <!-- Stempel = logo perusahaan yang dipilih, ditimpa tanda tangan -->
                        <?php if (!empty($st['logo'])) : ?>
                            <div class="st-stamp"><div class="st-crop"><img src="<?= base_url($st['logo']) ?>" alt="Stempel <?= esc($st['perusahaan']) ?>"></div></div>
                        <?php endif; ?>
                        <?php if ($adaTtd) : ?>
                            <img class="st-ttd" src="<?= site_url('SuratTugas/ttd/' . $st['id']) ?>" alt="Tanda tangan">
                        <?php endif; ?>
                    </div>
                    <div class="st-sign-nama"><?= esc($st['ttd_nama']) ?></div>
                    <div class="st-sign-jabatan"><?= esc($st['ttd_jabatan']) ?></div>
                </div>

                <div class="st-foot">
                    <?php foreach ($alamat as $a) : ?>
                        <div><?= esc($a) ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <?= view('SuratTugas/_scripts') ?>
</body>

</html>
