<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <title>Detail Pengadaan</title>
</head>

<body class="d-flex" style="min-height:100vh;overflow:hidden;">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <x-sidebar></x-sidebar>

    <main class="flex-grow-1 d-flex flex-column" style="height:100vh; overflow:hidden;">
        <x-header>Detail Pengadaan</x-header>

        <div class="flex-grow-1" style="overflow-y:auto;">
            <div class="container-fluid p-3">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">Detail Pengadaan</h3>
                        <p class="text-muted small mb-0">Rincian dan barang pada pengadaan</p>
                    </div>
                    <div>
                        <a href="{{ route('pengadaan.manage_pengadaan') }}"
                            class="btn btn-sm btn-secondary">← Kembali</a>
                    </div>
                </div>

                {{-- Prefilled form (read-only) --}}
                <form class="card mb-3">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label small text-muted">ID Pengadaan</label>
                                <input type="text" class="form-control form-control-sm"
                                    value="{{ $pengadaan->idpengadaan ?? ($pengadaan->id ?? '-') }}"
                                    readonly>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small text-muted">Tanggal</label>
                                <input type="text"
                                    class="form-control form-control-sm text-center"
                                    value="{{ isset($pengadaan->tgl_pengadaan) ? \Carbon\Carbon::parse($pengadaan->tgl_pengadaan)->format('d M Y H:i') : $pengadaan->created_at ?? '-' }}"
                                    readonly>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small text-muted">Vendor</label>
                                <input type="text"
                                    class="form-control form-control-sm text-center"
                                    value="{{ $pengadaan->vendor->nama_vendor ?? ($pengadaan->nama_vendor ?? '-') }}"
                                    readonly>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small text-muted">Dibuat oleh</label>
                                <input type="text"
                                    class="form-control form-control-sm text-center"
                                    value="{{ $pengadaan->user->username ?? ($pengadaan->username ?? '-') }}"
                                    readonly>
                            </div>
                        </div>

                        <hr>

                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Harga Satuan</label>
                                <input type="text" class="form-control form-control-sm text-end"
                                    value="{{ isset($pengadaan->harga_satuan) ? number_format($pengadaan->harga_satuan, 0, ',', '.') : '-' }}"
                                    readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Jumlah</label>
                                <input type="text" class="form-control form-control-sm text-end"
                                    value="{{ isset($pengadaan->jumlah) ? number_format($pengadaan->jumlah, 0, ',', '.') : $pengadaan->jumlah ?? '-' }}"
                                    readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Total Nilai</label>
                                <input type="text" class="form-control form-control-sm"
                                    value="{{ isset($pengadaan->total_nilai) ? number_format($pengadaan->total_nilai, 0, ',', '.') : '-' }}"
                                    readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Subtotal</label>
                                <input type="text" class="form-control form-control-sm"
                                    value="{{ isset($pengadaan->subtotal_nilai) ? number_format($pengadaan->subtotal_nilai, 0, ',', '.') : '-' }}"
                                    readonly>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small text-muted">PPN
                                    ({{ config('app.ppn_percent', 11) }}%)</label>
                                <input type="text" class="form-control form-control-sm"
                                    value="{{ isset($pengadaan->ppn) ? number_format($pengadaan->ppn, 0, ',', '.') : '-' }}"
                                    readonly>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label small text-muted">Status</label>
                                <input type="text"
                                    class="form-control form-control-sm text-center"
                                    value="{{ ($pengadaan->status ?? '0') == '1' ? 'Selesai' : 'Proses' }}"
                                    readonly>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- Details as table with prefilled inputs --}}
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th style="width:50px">#</th>
                                        <th>Barang</th>
                                        <th class="text-end">Harga Satuan</th>
                                        <th class="text-end">Jumlah</th>
                                        <th class="text-end">Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($detailPengadaans ?? [] as $d)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <input type="text"
                                                    class="form-control form-control-sm"
                                                    value="{{ $d->nama ?? ($d->nama_barang ?? '-') }}"
                                                    readonly>
                                            </td>
                                            <td class="text-end">
                                                <input type="text"
                                                    class="form-control form-control-sm text-end"
                                                    value="{{ isset($d->harga_satuan) ? number_format($d->harga_satuan, 0, ',', '.') : '-' }}"
                                                    readonly>
                                            </td>
                                            <td class="text-end">
                                                <input type="text"
                                                    class="form-control form-control-sm text-end"
                                                    value="{{ $d->jumlah ?? '-' }}" readonly>
                                            </td>
                                            <td class="text-end">
                                                <input type="text"
                                                    class="form-control form-control-sm text-end"
                                                    value="{{ isset($d->sub_total) ? number_format($d->sub_total, 0, ',', '.') : '-' }}"
                                                    readonly>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">No details
                                                found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</body>
