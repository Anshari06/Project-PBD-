<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
        crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <title>Detail Penerimaan</title>
</head>

<body class="d-flex" style="min-height: 100vh; overflow: hidden;">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    {{-- Sidebar --}}
    <x-sidebar></x-sidebar>

    {{-- Main Content Area --}}
    <main class="flex-grow-1 d-flex flex-column" style="height: 100vh; overflow: hidden;">
        {{-- Header Fixed --}}
        <x-header>Detail Penerimaan</x-header>

        {{-- Content Area with Scroll --}}
        <div class="flex-grow-1" style="overflow-y: auto;">

            <div class="container-fluid p-3">
                {{-- head --}}
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h2 class="mb-0">Detail Penerimaan</h2>
                            <p class="text-muted small mb-0">Manage detail of Penerimaan (incoming
                                receipts).</p>
                        </div>
                    </div>
                </div>

                {{-- back to penerimaan --}}
                <div class="mb-3">
                    <a href="{{ url('/manage_penerimaan') }}" class="btn btn-secondary btn-sm">Back
                        to Penerimaan</a>
                </div>

                {{-- Penerimaan summary (if available) --}}
                @if (isset($penerimaan))
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3"><strong>ID Penerimaan:</strong>
                                    {{ $penerimaan->idpenerimaan ?? ($penerimaan->id ?? '-') }}
                                </div>
                                <div class="col-md-3"><strong>Created At:</strong>
                                    {{ isset($penerimaan->created_at) ? \Carbon\Carbon::parse($penerimaan->created_at)->format('d M Y H:i') : (isset($penerimaan->tanggal) ? \Carbon\Carbon::parse($penerimaan->tanggal)->format('d M Y') : '-') }}
                                </div>
                                <div class="col-md-3"><strong>ID Pengadaan:</strong>
                                    {{ $penerimaan->idpengadaan ?? '-' }}</div>
                                <div class="col-md-3 text-end">
                                    <strong>Status:</strong>
                                    <div class="mt-1">
                                        <span
                                            class="badge {{ ($penerimaan->status ?? '0') == '1' ? 'bg-success' : 'bg-secondary' }}">{{ ($penerimaan->status ?? '0') == '1' ? 'Selesai' : 'Proses' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body text-center text-muted">No detail_penerimaan found
                        </div>
                    </div>
                @endif

                <!-- Detail Penerimaan Cards -->
                <div class="">
                    @forelse($detailPenerimaans ?? [] as $detail)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label small text-muted">ID Detail</label>
                                        <input type="text" class="form-control form-control-sm"
                                            value="{{ $detail->iddetail_penerimaan ?? ($detail->iddetail ?? ($detail->id ?? '-')) }}"
                                            readonly>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label small text-muted">Barang</label>
                                        <input type="text" class="form-control form-control-sm"
                                            value="{{ $detail->nama_barang ?? ($detail->nama ?? ($detail->barang_nama ?? '-')) }}"
                                            readonly>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label small text-muted">Harga
                                            Satuan</label>
                                        <input type="text"
                                            class="form-control form-control-sm text-end"
                                            value="{{ isset($detail->harga_satuan) ? number_format($detail->harga_satuan, 0, ',', '.') : (isset($detail->harga) ? number_format($detail->harga, 0, ',', '.') : '-') }}"
                                            readonly>
                                    </div>

                                    <div class="col-md-1">
                                        <label class="form-label small text-muted">Jumlah</label>
                                        <input type="text"
                                            class="form-control form-control-sm text-center"
                                            value="{{ $detail->jumlah ?? ($detail->qty ?? ($detail->kuantitas ?? '-')) }}"
                                            readonly>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label small text-muted">Subtotal</label>
                                        @php
                                            $subtotal =
                                                $detail->subtotal ?? ($detail->sub_total ?? null);
                                            if (!$subtotal) {
                                                if (isset($detail->harga_satuan, $detail->jumlah)) {
                                                    $subtotal =
                                                        $detail->harga_satuan * $detail->jumlah;
                                                } elseif (isset($detail->harga, $detail->qty)) {
                                                    $subtotal = $detail->harga * $detail->qty;
                                                }
                                            }
                                        @endphp
                                        <input type="text"
                                            class="form-control form-control-sm text-end"
                                            value="{{ isset($subtotal) ? number_format($subtotal, 0, ',', '.') : '-' }}"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card">
                            <div class="card-body text-center text-muted">No detail penerimaan found
                            </div>
                        </div>
                    @endforelse
                </div>

            </div>

        </div>
    </main>
</body>
