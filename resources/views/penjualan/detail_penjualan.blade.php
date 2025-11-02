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
    <title>Detail Penjualan</title>
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
        <x-header>Detail Penjualan</x-header>

        {{-- Content Area with Scroll --}}
        <div class="flex-grow-1" style="overflow-y: auto;">

            <div class="container-fluid p-3">
                {{-- head --}}
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h2 class="mb-0">Detail Penjualan</h2>
                            <p class="text-muted small mb-0">Manage detail of Penjualan.</p>
                        </div>
                    </div>
                </div>
                {{-- back to penjualan --}}
                <div class="mb-3">
                    <a href="{{ url('/manage_penjualan') }}" class="btn btn-secondary btn-sm">Back
                        to Penjualan</a>
                </div>

                {{-- Penjualan summary (if available) --}}
                @if (isset($penjualan))
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3"><strong>No. Nota:</strong>
                                    {{ $penjualan->no_nota ?? ($penjualan->idpenjualan ?? '-') }}
                                </div>
                                <div class="col-md-3"><strong>Tanggal:</strong>
                                    {{ isset($penjualan->created_at) ? \Carbon\Carbon::parse($penjualan->created_at)->format('d M Y H:i') : $penjualan->tanggal ?? '-' }}
                                </div>
                                <div class="col-md-3"><strong>Pelanggan:</strong>
                                    {{ $penjualan->pelanggan ?? '-' }}</div>
                                <div class="col-md-3 text-end">
                                    <strong>Total:</strong>
                                    {{ isset($penjualan->total) ? number_format($penjualan->total, 0, ',', '.') : '-' }}
                                    <div class="mt-1">
                                        <span
                                            class="badge {{ ($penjualan->status ?? 0) == 1 ? 'bg-success' : 'bg-secondary' }}">{{ ($penjualan->status ?? 0) == 1 ? 'Lunas' : 'Belum' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- <!-- Search Card -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <form action="{{ url('/manage_penjualan') }}" method="GET"
                            class="row g-2 align-items-end">
                            <div class="col-md-4">
                                <label for="q_no_nota" class="form-label visually-hidden">No.
                                    Nota</label>
                                <input type="text" name="no_nota" id="q_no_nota"
                                    class="form-control form-control-sm"
                                    placeholder="Search by No. Nota"
                                    value="{{ request('no_nota') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="q_pelanggan"
                                    class="form-label visually-hidden">Pelanggan</label>
                                <input type="text" name="pelanggan" id="q_pelanggan"
                                    class="form-control form-control-sm"
                                    placeholder="Search by Pelanggan"
                                    value="{{ request('pelanggan') }}">
                            </div>
                            <div class="col-md-2 d-grid">
                                <button type="submit"
                                    class="btn btn-primary btn-sm">Search</button>
                            </div>
                        </form>
                        <p class="mt-2 text-muted small mb-0">Use the fields above to filter
                            penjualan records.</p>
                    </div>
                </div> --}}

                <!-- Detail Penjualan Table -->
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
                                    @forelse($detailPenjualans ?? [] as $detail)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $detail->iddetail_penjualan ?? '-'}} </td>
                                            <td>{{ $detail->nama_barang ??  '-' }} <td>
                                            <td class="text-end">
                                                @if (isset($detail->harga_satuan))
                                                    {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                                @elseif(isset($detail->harga))
                                                    {{ number_format($detail->harga, 0, ',', '.') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {{ $detail->jumlah ?? ($detail->qty ?? ($detail->kuantitas ?? '-')) }}
                                            </td>
                                            <td class="text-end">
                                                @php
                                                    $subtotal =
                                                        $detail->subtotal ??
                                                        ($detail->sub_total ?? null);
                                                    if (!$subtotal) {
                                                        if (
                                                            isset(
                                                                $detail->harga_satuan,
                                                                $detail->jumlah,
                                                            )
                                                        ) {
                                                            $subtotal =
                                                                $detail->harga_satuan *
                                                                $detail->jumlah;
                                                        } elseif (
                                                            isset($detail->harga, $detail->qty)
                                                        ) {
                                                            $subtotal =
                                                                $detail->harga * $detail->qty;
                                                        }
                                                    }
                                                @endphp
                                                {{ isset($subtotal) ? number_format($subtotal, 0, ',', '.') : '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">No detail
                                                penjualan found</td>
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
