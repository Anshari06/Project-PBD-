<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <title>Manage Margin</title>
</head>

<body class="d-flex" style="min-height:100vh;overflow:hidden;">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <x-sidebar></x-sidebar>

    <main class="flex-grow-1 d-flex flex-column" style="height:100vh; overflow:hidden;">
        <x-header>Manage Margin</x-header>

        <div class="flex-grow-1" style="overflow-y:auto;">
            <div class="container-fluid p-3">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">Manage Margin</h3>
                        <p class="text-muted small mb-0">Daftar margin penjualan — tambah, edit,
                            hapus.</p>
                    </div>
                    <div>
                        <a href="{{ url('/manage_margin/create') }}"
                            class="btn btn-sm btn-primary">+ Add Margin</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:60px">No</th>
                                        <th>Waktu Margin</th>
                                        <th class="text-end">Nilai (%)</th>
                                        <th class="text-center">Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($margins as $m)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $m->created_at ?? '-' }}
                                            </td>
                                            <td class="text-end">
                                                {{ isset($m->persen) ? number_format($m->persen, 2, ',', '.') : '-' }}
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge {{ ($m->status ?? 0) == 1 ? 'bg-success' : 'bg-secondary' }}">{{ ($m->status ?? 0) == 1 ? 'Aktif' : 'Non-aktif' }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ url('/manage_margin/' . ($m->idmargin_penjualan ?? ($m->id ?? '')) . '/edit') }}"
                                                    class="btn btn-sm btn-warning me-1">Edit</a>
                                                <form action="#" method="POST" class="d-inline"
                                                    onsubmit="return confirm('Hapus margin ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">No margins
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
