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
    <title>Manage Penjualan</title>
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
        <x-header>Manage Penjualan</x-header>

        {{-- Content Area with Scroll --}}
        <div class="flex-grow-1" style="overflow-y: auto;">

            <div class="container-fluid p-3">
                {{-- head --}}
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h2 class="mb-0">Penjualan Management</h2>
                            <p class="text-muted small mb-0">Manage sales records — add, view,
                                search and
                                delete penjualan.</p>
                        </div>
                    </div>
                </div>

                <!-- Inline Add Penjualan Form -->
                <div class="card mb-3">
                    <div class="card-header py-2">
                        <strong class="small mb-0">Add Penjualan</strong>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/manage_penjualan') }}" method="POST"
                            class="row g-2 align-items-end">
                            @csrf
                            <div class="col-md-3">
                                <label for="tanggal" class="visually-hidden">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal"
                                    class="form-control form-control-sm" required
                                    value="{{ old('tanggal') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="no_nota" class="visually-hidden">No. Nota</label>
                                <input type="text" name="no_nota" id="no_nota"
                                    class="form-control form-control-sm" placeholder="No. Nota"
                                    required value="{{ old('no_nota') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="pelanggan" class="visually-hidden">Pelanggan</label>
                                <input type="text" name="pelanggan" id="pelanggan"
                                    class="form-control form-control-sm"
                                    placeholder="Nama Pelanggan" value="{{ old('pelanggan') }}">
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="submit" class="btn btn-primary btn-sm w-100">Add
                                    Penjualan</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Search Card -->
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
                </div>

                {{-- detail penjualan --}}
                <div>
                    <a href="{{ url('/detail_penjualan') }}" class="btn btn-info text-light btn-sm mb-3">Go to Detail Penjualan</a>
                </div>

                <!-- Penjualan Table -->
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:70px">No</th>
                                        <th style="width:120px">Tanggal</th>
                                        <th style="width:150px">No. Nota</th>
                                        <th>Pelanggan</th>
                                        <th style="width:140px">Total</th>
                                        <th style="width:120px">Status</th>
                                        <th style="width:180px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($penjualans ?? [] as $penjualan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $penjualan->tanggal ?? '-' }}</td>
                                            <td>{{ $penjualan->no_nota ?? '-' }}</td>
                                            <td>{{ $penjualan->pelanggan ?? '-' }}</td>
                                            <td>{{ isset($penjualan->total) ? number_format($penjualan->total, 0, ',', '.') : '-' }}
                                            </td>
                                            <td>
                                                <span
                                                    class="btn btn-sm {{ ($penjualan->status ?? 0) == 1 ? 'btn-success' : 'btn-secondary' }}">
                                                    {{ ($penjualan->status ?? 0) == 1 ? 'Lunas' : 'Belum' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ url('/manage_penjualan/' . ($penjualan->id ?? $penjualan->idpenjualan) . '/edit') }}"
                                                    class="btn btn-sm btn-warning me-1">Edit</a>
                                                <a href="{{ url('/manage_penjualan/' . ($penjualan->id ?? $penjualan->idpenjualan)) }}"
                                                    class="btn btn-sm btn-info me-1">View</a>
                                                <form
                                                    action="{{ url('/manage_penjualan/' . ($penjualan->id ?? $penjualan->idpenjualan)) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Hapus penjualan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">No
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
