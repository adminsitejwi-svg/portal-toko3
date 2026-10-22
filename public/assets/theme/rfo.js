/* =====================================================================
 *  Perilaku bersama halaman NOC Operation bergaya RFO (lihat rfo.css)
 *
 *  Dimuat dengan defer setelah jQuery & DataTables, jadi handler di bawah
 *  terpasang sebelum tabel halaman dibuat di $(document).ready.
 *
 *  - Info "Menampilkan ..." & pagination dipindah ke bawah kotak tabel.
 *  - Tombol .rfo-reset mengosongkan semua filter & pencarian halaman;
 *    event change/keyup dipicu ulang agar filter milik halaman ikut jalan.
 * ===================================================================== */
(function ($) {
    if (!$) return;

    $(document).on('init.dt', function (e, settings) {
        var $wrap = $(settings.nTableWrapper);
        var $scroll = $wrap.closest('.table-scroll');
        if (!$scroll.length || !$scroll.closest('.rfo-page').length) return;

        var $footer = $('<div class="rfo-footer flex flex-wrap items-center justify-between gap-3 mt-4"></div>');
        $footer.append($wrap.find('.dataTables_info'), $wrap.find('.dataTables_paginate'));
        $scroll.after($footer);
    });

    $(document).on('click', '.rfo-reset', function () {
        var $page = $(this).closest('.rfo-page');

        $page.find('select.filter-select, input.filter-select').val('').trigger('change');
        $page.find('#customSearch, .rfo-search input').val('').trigger('keyup');

        $page.find('table.dataTable').each(function () {
            $(this).DataTable().search('').columns().search('').draw();
        });
    });
})(window.jQuery);
