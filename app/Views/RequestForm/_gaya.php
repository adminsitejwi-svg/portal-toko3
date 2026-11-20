<style>
    .rq-alert { padding: 12px 16px; border-radius: 10px; font-size: 13px; margin-bottom: 16px; display: flex; gap: 8px; align-items: flex-start; }
    .rq-alert.err { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
    .rq-alert.ok { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .rq-alert.warn { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .rq-alert.info { background: #e3f5fe; color: #075985; border: 1px solid #b9e6fc; }
    .dark .rq-alert.err { background: rgba(220, 38, 38, .1); color: #fca5a5; border-color: rgba(220, 38, 38, .3); }
    .dark .rq-alert.ok { background: rgba(16, 185, 129, .1); color: #6ee7b7; border-color: rgba(16, 185, 129, .3); }
    .dark .rq-alert.warn { background: rgba(245, 158, 11, .1); color: #fcd34d; border-color: rgba(245, 158, 11, .3); }
    .dark .rq-alert.info { background: rgba(4, 169, 245, .1); color: #bae6fd; border-color: rgba(4, 169, 245, .3); }
    .rq-alert ul { margin: 4px 0 0 18px; list-style: disc; }

    .rq-tabs { display: flex; gap: 6px; border-bottom: 1px solid #e5e7eb; margin-bottom: 20px; flex-wrap: wrap; }
    .dark .rq-tabs { border-color: rgba(255, 255, 255, .1); }
    .rq-tab { padding: 10px 18px; font-weight: 600; font-size: 14px; color: #6b7280; border-bottom: 2px solid transparent; margin-bottom: -1px; cursor: pointer; display: flex; align-items: center; gap: 6px; }
    .rq-tab.active { color: #04a9f5; border-color: #04a9f5; }
    .rq-panel { display: none; }
    .rq-panel.active { display: block; }

    .rq-btn { display: inline-flex; align-items: center; justify-content: center; gap: 5px; font-size: 13px; font-weight: 600; padding: 7px 12px; border-radius: 8px; border: 1px solid #e5e7eb; background: #fff; color: #374151; cursor: pointer; text-decoration: none; white-space: nowrap; }
    .rq-btn:hover { background: #f9fafb; }
    .rq-btn.biru { background: #04a9f5; border-color: #04a9f5; color: #fff; }
    .rq-btn.biru:hover { background: #0396e2; }
    .rq-btn.hijau { background: #10b981; border-color: #10b981; color: #fff; }
    .rq-btn.hijau:hover { background: #059669; }
    .rq-btn.kuning { color: #b45309; border-color: #fde68a; }
    .rq-btn.merah { color: #dc2626; border-color: #fecaca; }
    .rq-btn.merah:hover { background: #fef2f2; }
    .rq-btn.kecil { padding: 5px 9px; font-size: 12px; }
    .rq-btn[disabled] { opacity: .5; cursor: not-allowed; }
    .dark .rq-btn { background: #1d2630; border-color: rgba(255, 255, 255, .12); color: #e2e8f0; }
    .dark .rq-btn.biru { background: #04a9f5; border-color: #04a9f5; color: #fff; }
    .dark .rq-btn.hijau { background: #10b981; border-color: #10b981; color: #fff; }
    .dark .rq-btn.kuning { color: #fcd34d; border-color: rgba(245, 158, 11, .4); }
    .dark .rq-btn.merah { color: #fca5a5; border-color: rgba(220, 38, 38, .4); }

    .rq-badge { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 600; padding: 3px 9px; border-radius: 999px; background: #f1f5f9; color: #475569; white-space: nowrap; }
    .rq-badge.biru { background: #e3f5fe; color: #0284c7; }
    .rq-badge.hijau { background: #d1fae5; color: #047857; }
    .rq-badge.kuning { background: #fef3c7; color: #b45309; }
    .rq-badge.merah { background: #fee2e2; color: #b91c1c; }
    .dark .rq-badge { background: rgba(255, 255, 255, .08); color: #cbd5e1; }
    .dark .rq-badge.biru { background: rgba(4, 169, 245, .15); color: #7dd3fc; }
    .dark .rq-badge.hijau { background: rgba(16, 185, 129, .15); color: #6ee7b7; }
    .dark .rq-badge.kuning { background: rgba(245, 158, 11, .15); color: #fcd34d; }
    .dark .rq-badge.merah { background: rgba(220, 38, 38, .15); color: #fca5a5; }

    .rq-meta { font-size: 12px; color: #6b7280; }
    .dark .rq-meta { color: #94a3b8; }
    .rq-kosong { text-align: center; padding: 48px 16px; color: #6b7280; }
    .rq-kosong i { font-size: 42px; display: block; margin-bottom: 8px; color: #94a3b8; }

    .rq-input { width: 100%; padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; background: #fff; color: inherit; }
    .dark .rq-input { background: #1d2630; border-color: rgba(255, 255, 255, .15); }
    .rq-input:focus { outline: none; border-color: #04a9f5; box-shadow: 0 0 0 3px rgba(4, 169, 245, .15); }
    textarea.rq-input { resize: vertical; min-height: 80px; }
    .rq-label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
    .rq-field { margin-bottom: 14px; }

    .rq-tbl { width: 100%; border-collapse: collapse; font-size: 14px; }
    .rq-tbl th { text-align: left; font-size: 12px; text-transform: uppercase; letter-spacing: .03em; color: #6b7280; padding: 10px 12px; border-bottom: 1px solid #e5e7eb; white-space: nowrap; }
    .rq-tbl td { padding: 12px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
    .dark .rq-tbl th, .dark .rq-tbl td { border-color: rgba(255, 255, 255, .08); }

    .rq-progress { height: 6px; border-radius: 999px; background: #e5e7eb; overflow: hidden; min-width: 90px; }
    .rq-progress > span { display: block; height: 100%; background: #10b981; }
    .dark .rq-progress { background: rgba(255, 255, 255, .1); }

    .rq-modal { position: fixed; inset: 0; z-index: 2000; background: rgba(15, 23, 42, .55); display: none; align-items: flex-start; justify-content: center; padding: 40px 16px; overflow-y: auto; }
    .rq-modal.show { display: flex; }
    .rq-box { background: #fff; border-radius: 14px; width: 100%; max-width: 720px; box-shadow: 0 25px 60px rgba(0, 0, 0, .25); }
    .dark .rq-box { background: #263240; color: #e2e8f0; }
    .rq-box-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 16px 22px; border-bottom: 1px solid #e5e7eb; }
    .rq-box-head h3 { font-size: 17px; font-weight: 700; }
    .rq-box-body { padding: 20px 22px; }
    .rq-box-foot { display: flex; justify-content: flex-end; gap: 8px; padding: 14px 22px; border-top: 1px solid #e5e7eb; flex-wrap: wrap; }
    .dark .rq-box-head, .dark .rq-box-foot { border-color: rgba(255, 255, 255, .1); }

    @media (max-width: 640px) {
        .rfo-page { padding: 16px !important; }
        .rq-tbl-wrap { overflow-x: auto; }
    }
</style>
