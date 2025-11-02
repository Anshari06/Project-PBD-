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
                            <p class="text-muted small mb-0">Manage detail of Penerimaan (incoming receipts).</p>
                        </div>
                    </div>
                </div>

                {{-- back to penerimaan --}}
                <div class="mb-3">
                    <a href="{{ url('/manage_penerimaan') }}" class="btn btn-secondary btn-sm">Back to Penerimaan</a>
                </div>

                {{-- Penerimaan summary (if available) --}}
                @if(isset($penerimaan))
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3"><strong>ID Penerimaan:</strong> {{ $penerimaan->idpenerimaan ?? ($penerimaan->id ?? '-') }}</div>
                                <div class="col-md-3"><strong>Created At:</strong> {{ isset($penerimaan->created_at) ? \Carbon\Carbon::parse($penerimaan->created_at)->format('d M Y H:i') : (isset($penerimaan->tanggal) ? \Carbon\Carbon::parse($penerimaan->tanggal)->format('d M Y') : '-') }}</div>
                                <div class="col-md-3"><strong>ID Pengadaan:</strong> {{ $penerimaan->idpengadaan ?? '-' }}</div>
                                <div class="col-md-3 text-end">
                                    <strong>Status:</strong>
                                    <div class="mt-1">
                                        <span class="badge {{ ($penerimaan->status ?? '0') == '1' ? 'bg-success' : 'bg-secondary' }}">{{ ($penerimaan->status ?? '0') == '1' ? 'Selesai' : 'Proses' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Detail Penerimaan Table -->
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:60px">No</th>
                                        <th style="width:120px">ID Detail</th>
                                        <th>Barang</th>
                                        <th style="width:140px" class="text-end">Harga Satuan</th>
                                        <th style="width:100px" class="text-center">Jumlah</th>
                                        <th style="width:140px" class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($detailPenerimaans ?? [] as $detail)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $detail->iddetail_penerimaan ?? $detail->iddetail ?? $detail->id ?? '-' }}</td>
                                            <td>{{ $detail->nama_barang ?? $detail->nama ?? $detail->barang_nama ?? '-' }}</td>
                                            <td class="text-end">
                                                @if(isset($detail->harga_satuan))
                                                    {{ number_format($detail->harga_satuan,0,',','.') }}
                                                @elseif(isset($detail->harga))
                                                    {{ number_format($detail->harga,0,',','.') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $detail->jumlah ?? $detail->qty ?? $detail->kuantitas ?? '-' }}</td>
                                            <td class="text-end">
                                                @php
                                                    $subtotal = $detail->subtotal ?? ($detail->sub_total ?? null);
                                                    if(!$subtotal) {
                                                        if(isset($detail->harga_satuan,$detail->jumlah)) {
                                                            $subtotal = $detail->harga_satuan * $detail->jumlah;
                                                        } elseif(isset($detail->harga,$detail->qty)) {
                                                            $subtotal = $detail->harga * $detail->qty;
                                                        }
                                                    }
                                                @endphp
                                                {{ isset($subtotal) ? number_format($subtotal,0,',','.') : '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">No detail penerimaan found</td>
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
