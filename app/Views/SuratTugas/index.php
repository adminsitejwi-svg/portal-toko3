<?php use App\Models\SuratTugasModel; ?>
<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title>Surat Tugas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- DataTables core + Buttons -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <?= view('SuratTugas/_head') ?>
</head>

<body class="text-[#37474f] dark:text-[#bfc8d6]">
    <?= view('SuratTugas/_sidebar') ?>

        <div class="rfo-page p-6">
            <?php
            $fmtTgl = static fn ($d) => $d ? date('d-m-Y', strtotime($d)) : '-';

            // Badge per status pelaksanaan: [kelas warna, ikon]
            $statusBadge = [
                'Akan Datang' => ['rb-blue', 'ti-calendar-time'],
                'Berlangsung' => ['rb-amber', 'ti-loader-2'],
                'Selesai'     => ['rb-green', 'ti-circle-check-filled'],
            ];

            // Ringkasan untuk kartu statistik
            $bulanIni  = date('Y-m');
            $total     = count($surat);
            $jmlBulan  = count(array_filter($surat, static fn ($r) => substr($r['tanggal_surat'], 0, 7) === $bulanIni));
            $jmlJalan  = count(array_filter($surat, static fn ($r) => SuratTugasModel::status($r) === 'Berlangsung'));
            $jmlDatang = count(array_filter($surat, static fn ($r) => SuratTugasModel::status($r) === 'Akan Datang'));

            // Kartu yang diklik: ?tampil=bulan-ini | berlangsung | akan-datang (selain itu: semua)
            $tampilOpsi = [
                'bulan-ini'   => ['Surat Bulan Ini', static fn ($r) => substr($r['tanggal_surat'], 0, 7) === $bulanIni],
                'berlangsung' => ['Tugas Berlangsung', static fn ($r) => SuratTugasModel::status($r) === 'Berlangsung'],
                'akan-datang' => ['Tugas Akan Datang', static fn ($r) => SuratTugasModel::status($r) === 'Akan Datang'],
            ];
            $tampil     = service('request')->getGet('tampil');
            $tampil     = isset($tampilOpsi[$tampil]) ? $tampil : '';
            $suratTabel = $tampil ? array_values(array_filter($surat, $tampilOpsi[$tampil][1])) : $surat;
            ?>

            <!-- JUDUL -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic"><i class="ti ti-file-text"></i></span>
                    <div>
                        <h1 class="rfo-title">Surat Tugas</h1>
                        <?php if ($tampil) : ?>
                            <p class="rfo-subtitle">Menampilkan: <b><?= $tampilOpsi[$tampil][0] ?></b> (<?= count($suratTabel) ?> data)</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <?php if ($tampil) : ?>
                        <a href="<?= site_url('SuratTugas') ?>" class="rfo-btn rfo-btn-gray">
                            <i class="ti ti-list"></i> Semua Surat
                        </a>
                    <?php endif; ?>
                    <a href="<?= site_url('SuratTugas/create') ?>" class="rfo-btn rfo-btn-blue">
                        <i class="ti ti-plus"></i> Tambah Surat Tugas
                    </a>
                </div>
            </div>

            <!-- KARTU STATISTIK -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
                <a href="<?= site_url('SuratTugas') ?>" class="rfo-stat rfo-stat-blue rfo-stat-link<?= $tampil === '' ? ' is-active' : '' ?>">
                    <span class="rfo-stat-ic"><i class="ti ti-file-text"></i></span>
                    <div>
                        <div class="rfo-stat-label">Total Surat</div>
                        <div class="rfo-stat-value"><?= $total ?></div>
                        <div class="rfo-stat-note">Data keseluruhan</div>
                    </div>
                    <i class="ti ti-chart-bar rfo-stat-chart"></i>
                </a>
                <a href="<?= site_url('SuratTugas') . '?tampil=bulan-ini' ?>" class="rfo-stat rfo-stat-purple rfo-stat-link<?= $tampil === 'bulan-ini' ? ' is-active' : '' ?>">
                    <span class="rfo-stat-ic"><i class="ti ti-calendar-month"></i></span>
                    <div>
                        <div class="rfo-stat-label">Bulan Ini</div>
                        <div class="rfo-stat-value"><?= $jmlBulan ?></div>
                        <div class="rfo-stat-note"><?= SuratTugasModel::BULAN[(int) date('n')] . ' ' . date('Y') ?></div>
                    </div>
                    <i class="ti ti-chart-bar rfo-stat-chart"></i>
                </a>
                <a href="<?= site_url('SuratTugas') . '?tampil=berlangsung' ?>" class="rfo-stat rfo-stat-amber rfo-stat-link<?= $tampil === 'berlangsung' ? ' is-active' : '' ?>">
                    <span class="rfo-stat-ic"><i class="ti ti-loader-2"></i></span>
                    <div>
                        <div class="rfo-stat-label">Berlangsung</div>
                        <div class="rfo-stat-value"><?= $jmlJalan ?></div>
                        <div class="rfo-stat-note">Tugas hari ini</div>
                    </div>
                    <i class="ti ti-chart-bar rfo-stat-chart"></i>
                </a>
                <a href="<?= site_url('SuratTugas') . '?tampil=akan-datang' ?>" class="rfo-stat rfo-stat-green rfo-stat-link<?= $tampil === 'akan-datang' ? ' is-active' : '' ?>">
                    <span class="rfo-stat-ic"><i class="ti ti-calendar-time"></i></span>
                    <div>
                        <div class="rfo-stat-label">Akan Datang</div>
                        <div class="rfo-stat-value"><?= $jmlDatang ?></div>
                        <div class="rfo-stat-note">Belum dimulai</div>
                    </div>
                    <i class="ti ti-chart-bar rfo-stat-chart"></i>
                </a>
            </div>

            <!-- FILTER + TABEL -->
            <div class="rfo-panel">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
                    <div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <label class="rfo-field">
                                <i class="ti ti-building-skyscraper" style="color:#2f6bff"></i>
                                <select id="filterPerusahaan" class="filter-select is-wide" title="Filter Perusahaan">
                                    <option value="">Semua Perusahaan</option>
                                    <?php foreach ($perusahaan as $nama) : ?>
                                        <option value="<?= esc($nama) ?>"><?= esc($nama) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                            <label class="rfo-field">
                                <i class="ti ti-progress-check" style="color:#2f6bff"></i>
                                <select id="filterStatus" class="filter-select" title="Filter Status">
                                    <option value="">Semua Status</option>
                                    <?php foreach (array_keys($statusBadge) as $s) : ?>
                                        <option value="<?= $s ?>"><?= $s ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                            <label class="rfo-field">
                                <i class="ti ti-tag" style="color:#6d5dfc"></i>
                                <select id="filterNomor" class="filter-select" title="Filter Nomor Surat">
                                    <option value="">Semua Nomor</option>
                                    <?php foreach ($suratTabel as $row) : ?>
                                        <option value="<?= esc($row['nomor']) ?>"><?= esc($row['nomor']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <div class="rfo-search">
                            <i class="ti ti-search"></i>
                            <input type="text" id="customSearch" placeholder="Cari nomor, teknisi, lokasi, serial number..." />
                        </div>
                        <button type="button" id="btnReset" class="rfo-ghost-btn"><i class="ti ti-refresh"></i> Reset</button>
                        <div id="exportArea"></div>
                    </div>
                </div>

                <!-- TABLE -->
                <div class="table-scroll">
                    <table id="stTable" class="rfo-table display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Aksi</th>
                                <th>Nomor Surat</th>
                                <th>Perusahaan</th>
                                <th>Tanggal Surat</th>
                                <th>Hari / Tanggal Tugas</th>
                                <th>Kegiatan</th>
                                <th>Keperluan</th>
                                <th>Teknisi</th>
                                <th>Lokasi</th>
                                <th>Perangkat</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($suratTabel as $row) : ?>
                                <?php
                                $status = SuratTugasModel::status($row);
                                [$stKelas, $stIkon] = $statusBadge[$status];
                                $perangkat = SuratTugasModel::perangkat($row);
                                // Teks perangkat lengkap untuk tooltip, pencarian & export (merk jenis (SN))
                                $perangkatTeks = implode('; ', array_map(
                                    static fn ($p) => trim(($p['merk'] ?? '') . ' ' . ($p['jenis'] ?? '')) . ' (' . (($p['sn'] ?? '') ?: '-') . ')',
                                    $perangkat
                                ));
                                ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td>
                                        <a href="<?= site_url('SuratTugas/view/' . $row['id']) ?>" class="act act-view" title="Lihat / Print">
                                            <i class="ti ti-eye"></i> View
                                        </a>
                                        <a href="<?= site_url('SuratTugas/edit/' . $row['id']) ?>" class="act act-edit" title="Edit">
                                            <i class="ti ti-pencil"></i> Edit
                                        </a>
                                        <button type="button" onclick="confirmDelete(<?= $row['id'] ?>)" class="act act-del" title="Hapus">
                                            <i class="ti ti-trash"></i> Hapus
                                        </button>
                                    </td>
                                    <td class="rfo-id"><?= esc($row['nomor']); ?></td>
                                    <td data-search="<?= esc($row['perusahaan']) ?>">
                                        <span class="cell-ic"><i class="ti ti-building-skyscraper" style="color:#2f6bff"></i><?= esc($row['perusahaan']); ?></span>
                                    </td>
                                    <td data-order="<?= esc($row['tanggal_surat']) ?>">
                                        <span class="cell-ic"><i class="ti ti-calendar" style="color:#2f6bff"></i><?= $fmtTgl($row['tanggal_surat']); ?></span>
                                    </td>
                                    <td data-order="<?= esc($row['tanggal_mulai']) ?>">
                                        <span class="cell-ic"><i class="ti ti-calendar-event" style="color:#ef4444"></i><?= esc(SuratTugasModel::hariTugas($row) . ', ' . SuratTugasModel::tanggalTugas($row)); ?></span>
                                    </td>
                                    <td><span class="cell-clip" title="<?= esc($row['kegiatan'], 'attr') ?>"><?= esc($row['kegiatan']); ?></span></td>
                                    <td>
                                        <span class="cell-ic"><i class="ti ti-tool" style="color:#2f6bff"></i><span class="cell-clip" title="<?= esc($row['keperluan'], 'attr') ?>"><?= esc($row['keperluan']); ?></span></span>
                                    </td>
                                    <td>
                                        <span class="cell-ic"><i class="ti ti-users" style="color:#6d5dfc"></i><?= esc(SuratTugasModel::gabungNama(SuratTugasModel::teknisi($row))); ?></span>
                                    </td>
                                    <td>
                                        <span class="cell-ic"><i class="ti ti-map-pin" style="color:#ef4444"></i><span class="cell-clip" title="<?= esc($row['lokasi'], 'attr') ?>"><?= esc($row['lokasi']); ?></span></span>
                                    </td>
                                    <td data-search="<?= esc($perangkatTeks) ?>" data-export="<?= esc($perangkatTeks) ?>">
                                        <span class="rb rb-purple" title="<?= esc($perangkatTeks, 'attr') ?>"><i class="ti ti-device-desktop"></i><?= count($perangkat) ?> perangkat</span>
                                    </td>
                                    <td data-search="<?= esc($status) ?>"><span class="rb <?= $stKelas ?>"><i class="ti <?= $stIkon ?>"></i><?= esc($status); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div id="stFooter" class="rfo-footer flex flex-wrap items-center justify-between gap-3 mt-4"></div>
            </div>
        </div>
    </div>

    <script>
        // ====== DELETE ======
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Data?',
                text: 'Data yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= site_url('SuratTugas/delete/') ?>" + id;
                }
            });
        }

        function isTableEmpty(table) {
            return table.rows({ search: 'applied' }).data().length === 0;
        }

        function showEmptyExportAlert() {
            Swal.fire({
                icon: 'warning',
                title: 'Data Kosong',
                text: 'Tidak ada data yang bisa diexport.',
                confirmButtonColor: '#04a9f5'
            });
        }
    </script>

    <?= view('SuratTugas/_scripts') ?>

    <script>
        $(document).ready(function() {
            const judulExport = 'Data Surat Tugas';
            const exportConfig = {
                exportOptions: {
                    // Kolom Aksi tidak ikut diexport
                    columns: function(idx) {
                        return idx !== 1;
                    },
                    format: {
                        body: function(data, row, column, node) {
                            // Kolom Perangkat: export daftar lengkap (merk jenis (SN)), bukan jumlahnya
                            const lengkap = node && node.getAttribute ? node.getAttribute('data-export') : null;
                            if (lengkap !== null) return lengkap;
                            const tmp = document.createElement('div');
                            tmp.innerHTML = data;
                            return tmp.textContent.trim();
                        }
                    }
                }
            };

            // Tombol export dengan peringatan bila tabel kosong
            const tombolExport = (jenis, teks, ikon, tambahan) => Object.assign({
                extend: jenis,
                text: '<i class="ti ' + ikon + '"></i> ' + teks,
                title: judulExport,
                ...exportConfig,
                action: function(e, dt, button, config) {
                    if (isTableEmpty(dt)) return showEmptyExportAlert();
                    $.fn.dataTable.ext.buttons[jenis].action.call(this, e, dt, button, config);
                }
            }, tambahan || {});

            const table = $('#stTable').DataTable({
                // Tampilkan semua data yang diinput (tanpa pilihan 10/15/25/50 & tanpa halaman)
                paging: false,
                order: [],
                columnDefs: [{
                    targets: [0, 1],
                    orderable: false,
                    searchable: false
                }],
                dom: "lBfrtip",
                buttons: [{
                    extend: 'collection',
                    text: '<i class="ti ti-download"></i> Export',
                    className: 'export-toggle',
                    buttons: [
                        tombolExport('copyHtml5', 'Copy', 'ti-copy'),
                        tombolExport('csvHtml5', 'Export CSV', 'ti-file-text'),
                        tombolExport('excelHtml5', 'Export Excel', 'ti-file-spreadsheet'),
                        tombolExport('pdfHtml5', 'Export PDF', 'ti-file-type-pdf', {
                            orientation: 'landscape',
                            pageSize: 'A4',
                            customize: function(doc) {
                                doc.styles.tableHeader = {
                                    fillColor: '#04a9f5',
                                    color: '#fff',
                                    bold: true,
                                    alignment: 'left'
                                };
                                doc.defaultStyle.fontSize = 9;
                                doc.content[1].layout = {
                                    hLineWidth: () => 0.5,
                                    vLineWidth: () => 0.5,
                                    hLineColor: () => '#e0e0e0',
                                    vLineColor: () => '#e0e0e0'
                                };
                            }
                        })
                    ]
                }],
                language: {
                    lengthMenu: "_MENU_",
                    info: "Menampilkan _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 data",
                    infoFiltered: "(disaring dari _MAX_ data)",
                    emptyTable: "Data Surat Tugas belum tersedia",
                    zeroRecords: "Tidak ada data yang cocok dengan pencarian",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Selanjutnya"
                    }
                }
            });

            table.on('draw.dt order.dt search.dt', function() {
                let i = table.page.info().start;
                table.column(0, {
                    page: 'current',
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell) {
                    cell.innerHTML = ++i;
                });
            });
            table.draw();

            $('#exportArea').append($('.dt-buttons'));
            $('#stFooter').append($('.dataTables_info'), $('.dataTables_paginate'));

            // ====== FILTER DROPDOWN (cocok persis pada kolom) ======
            function filterColumn(col, value) {
                const re = value ? '^' + $.fn.dataTable.util.escapeRegex(value) + '$' : '';
                table.column(col).search(re, true, false).draw();
            }
            $('#filterNomor').on('change', function() {
                filterColumn(2, this.value);
            });
            $('#filterPerusahaan').on('change', function() {
                filterColumn(3, this.value);
            });
            $('#filterStatus').on('change', function() {
                filterColumn(11, this.value);
            });

            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Reset: kosongkan semua filter & pencarian
            $('#btnReset').on('click', function() {
                $('#filterPerusahaan, #filterStatus, #filterNomor, #customSearch').val('');
                table.search('').columns().search('').draw();
            });
        });
    </script>
</body>

</html>
