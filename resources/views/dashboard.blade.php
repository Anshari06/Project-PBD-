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
    <title>Dashboard</title>
</head>

<body class="d-flex" style="min-height: 100vh; overflow: hidden;">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    {{-- Sidebar --}}
    <x-sidebar></x-sidebar>

    {{-- Main Content Area --}}
    <main class="flex-grow-1 d-flex flex-column" style="height: 100vh; overflow: auto;">
        {{-- Header Fixed --}}
        <x-header>Dashboard</x-header>

        {{-- Content Area --}}
        <div class="flex-grow-1">
            <div class="container-fluid p-3">
                <h2 class="mt-0 mb-2">Welcome</h2>
                <p class="mb-3">Your data is right here</p>

                {{-- Statistik cards (akan menampilkan jumlah dari DB) --}}
                <div class="container-fluid mt-3">
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <div class="card shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <i class="bi bi-people fs-1 text-primary me-3"></i>
                                    <div>
                                        <div class="text-muted small">Total Users</div>
                                        <div class="fs-3 fw-bold">{{ $usersCount ?? '—' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <div class="card shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <i class="bi bi-box fs-1 text-success me-3"></i>
                                    <div>
                                        <div class="text-muted small">Barang Ready</div>
                                        <div class="fs-3 fw-bold">{{ $barangCount ?? '—' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-md-3">
                            <div class="card shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <i class="bi bi-building-fill fs-1 text-warning me-3"></i>
                                    <div>
                                        <div class="text-muted small">Vendor Aktif</div>
                                        <div class="fs-3 fw-bold">{{ $vendorCount ?? '—' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Table penjualan terbaru --}}
            <div class="container-fluid p-3">
                <div class="card">
                    <div class="card-header">
                        <strong>Latest Penjualan</strong>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:60px">#</th>
                                        <th>ID Penjualan</th>
                                        <th>Tanggal</th>
                                        <th class="text-end">Subtotal</th>
                                        <th class="text-end">Total</th>
                                        <th>ID Margin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($penjualanterbaru as $i => $p)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $p->idpenjualan ?? '-' }}</td>
                                            <td>{{ isset($p->created_at) ? \Carbon\Carbon::parse($p->created_at)->format('d M Y H:i') : '-' }}
                                            </td>
                                            <td class="text-end">
                                                {{ isset($p->subtotal_nilai) ? number_format($p->subtotal_nilai, 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="text-end">
                                                {{ isset($p->total) ? number_format($p->total, 0, ',', '.') : '-' }}
                                            </td>
                                            <td>{{ $p->idmargin_penjualan ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">No recent
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
