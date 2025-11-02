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
    <title>Manage Penerimaan</title>
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
        <x-header>Manage Penerimaan</x-header>

        {{-- Content Area with Scroll --}}
        <div class="flex-grow-1" style="overflow-y: auto;">

            <div class="container-fluid p-3">
                {{-- head --}}
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h2 class="mb-0">Penerimaan Management</h2>
                            <p class="text-muted small mb-0">Manage incoming receipts (penerimaan) — add, view and search records.</p>
                        </div>
                    </div>
                </div>

                <!-- Inline Add Penerimaan Form -->
                <div class="card mb-3">
                    <div class="card-header py-2">
                        <strong class="small mb-0">Add Penerimaan</strong>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/manage_penerimaan') }}" method="POST" class="row g-2 align-items-end">
                            @csrf
                            <div class="col-md-3">
                                <label for="tanggal" class="visually-hidden">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal" class="form-control form-control-sm" required value="{{ old('tanggal') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="no_bukti" class="visually-hidden">No. Bukti</label>
                                <input type="text" name="no_bukti" id="no_bukti" class="form-control form-control-sm" placeholder="No. Bukti" required value="{{ old('no_bukti') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="supplier" class="visually-hidden">Supplier</label>
                                <input type="text" name="supplier" id="supplier" class="form-control form-control-sm" placeholder="Supplier" value="{{ old('supplier') }}">
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="submit" class="btn btn-primary btn-sm w-100">Add Penerimaan</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Search Card -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <form action="{{ url('/manage_penerimaan') }}" method="GET" class="row g-2 align-items-end">
                            <div class="col-md-4">
                                <label for="q_no_bukti" class="form-label visually-hidden">No. Bukti</label>
                                <input type="text" name="no_bukti" id="q_no_bukti" class="form-control form-control-sm" placeholder="Search by No. Bukti" value="{{ request('no_bukti') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="q_supplier" class="form-label visually-hidden">Supplier</label>
                                <input type="text" name="supplier" id="q_supplier" class="form-control form-control-sm" placeholder="Search by Supplier" value="{{ request('supplier') }}">
                            </div>
                            <div class="col-md-2 d-grid">
                                <button type="submit" class="btn btn-primary btn-sm">Search</button>
                            </div>
                        </form>
                        <p class="mt-2 text-muted small mb-0">Use the fields above to filter penerimaan records.</p>
                    </div>
                </div>

                <!-- Penerimaan Table -->
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:70px">No</th>
                                        <th style="width:140px">Tanggal</th>
                                        <th style="width:160px">No. Bukti</th>
                                        <th>Supplier</th>
                                        <th style="width:140px" class="text-end">Total</th>
                                        <th style="width:120px">Status</th>
                                        <th style="width:180px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($penerimaans ?? [] as $penerimaan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ isset($penerimaan->tanggal) ? \Carbon\Carbon::parse($penerimaan->tanggal)->format('d M Y') : ($penerimaan->created_at ?? '-') }}</td>
                                            <td>{{ $penerimaan->no_bukti ?? $penerimaan->nobukti ?? '-' }}</td>
                                            <td>{{ $penerimaan->supplier ?? '-' }}</td>
                                            <td class="text-end">{{ isset($penerimaan->total) ? number_format($penerimaan->total,0,',','.') : '-' }}</td>
                                            <td>
                                                <span class="btn btn-sm {{ ($penerimaan->status ?? 0) == 1 ? 'btn-success' : 'btn-secondary' }}">{{ ($penerimaan->status ?? 0) == 1 ? 'Selesai' : 'Proses' }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ url('/manage_penerimaan/' . ($penerimaan->id ?? $penerimaan->idpenerimaan) . '/edit') }}" class="btn btn-sm btn-warning me-1">Edit</a>
                                                <a href="{{ url('/manage_penerimaan/' . ($penerimaan->id ?? $penerimaan->idpenerimaan)) }}" class="btn btn-sm btn-info me-1">View</a>
                                                <form action="{{ url('/manage_penerimaan/' . ($penerimaan->id ?? $penerimaan->idpenerimaan)) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus penerimaan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">No penerimaan found</td>
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
