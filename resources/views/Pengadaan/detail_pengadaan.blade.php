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
                            <div class="col-md-2">
                                <label class="form-label small text-muted">ID Pengadaan</label>
                                <input type="text" class="form-control form-control-sm"
                                    value="{{ $pengadaan->idpengadaan ?? ($pengadaan->id ?? '-') }}"
                                    readonly>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small text-muted">Tanggal</label>
                                <input type="text"
                                    class="form-control form-control-sm text-center"
                                    value="{{ \Carbon\Carbon::parse($pengadaan->created_at)->format('d M Y H:i') . $pengadaan->created_at ?? '-' }}"
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
                        @foreach ($detailPengadaan as $detail)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 class="text-muted">Detail Barang {{ $loop->iteration }}</h6>
                                    <div class="row g-3">

                                        <div class="col-md-3">
                                            <label class="form-label small text-muted">Harga
                                                Satuan</label>
                                            <input type="text"
                                                class="form-control form-control-sm text-end"
                                                value="{{ number_format($detail->harga_satuan, 0, ',', '.') }}"
                                                readonly>
                                        </div>

                                        <div class="col-md-3">
                                            <label
                                                class="form-label small text-muted">Jumlah</label>
                                            <input type="text"
                                                class="form-control form-control-sm text-end"
                                                value="{{ number_format($detail->jumlah, 0, ',', '.') }}"
                                                readonly>
                                        </div>

                                        <div class="col-md-3">
                                            <label
                                                class="form-label small text-muted">Subtotal</label>
                                            <input type="text"
                                                class="form-control form-control-sm"
                                                value="{{ number_format($detail->subtotal_nilai, 0, ',', '.') }}"
                                                readonly>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label small text-muted">PPN
                                                ({{ config('app.ppn_percent', 11) }}%)
                                            </label>
                                            <input type="text"
                                                class="form-control form-control-sm"
                                                value="{{ config('app.ppn_percent', 11) }}%"
                                                readonly>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label small text-muted">Total
                                                Nilai</label>
                                            <input type="text"
                                                class="form-control form-control-sm"
                                                value="{{ number_format($detail->total_nilai, 0, ',', '.') }}"
                                                readonly>
                                        </div>

                                        <div class="col-md-3">
                                            <label
                                                class="form-label small text-muted">Status</label>

                                            @php
                                                $status = $detail->status ?? '';
                                                switch ($status) {
                                                    case 'P':
                                                        $cls = 'badge bg-primary';
                                                        $lbl = 'Pending';
                                                        break;
                                                    case 'O':
                                                        $cls = 'badge bg-warning text-dark';
                                                        $lbl = 'On-Progress';
                                                        break;
                                                    case 'S':
                                                        $cls = 'badge bg-success';
                                                        $lbl = 'Complete';
                                                        break;
                                                    case 'B':
                                                        $cls = 'badge bg-danger';
                                                        $lbl = 'Batal';
                                                        break;
                                                    default:
                                                        $cls = 'badge bg-secondary';
                                                        $lbl = $status ?: '-';
                                                        break;
                                                }
                                            @endphp

                                            <div >
                                                <span
                                                    class="form-control form-control-sm text-center text-white {{ $cls }}">{{ $lbl }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label small text-muted">Total
                                                Diterima</label>
                                            <input type="text"
                                                class="form-control form-control-sm bg-success text-white"
                                                value="{{ number_format($detail->total_diterima, 0, ',', '.') }}"
                                                readonly>
                                        </div>

                                        <div class="col-md-2">
                                            <label class="form-label small text-muted">Sisa</label>
                                            <input type="text"
                                                class="form-control form-control-sm bg-danger text-white"
                                                value="{{ number_format($detail->sisa_terima, 0, ',', '.') }}"
                                                readonly>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach

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
                                    @forelse($detailbarang ?? [] as $d)
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
