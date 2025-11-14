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
                            <p class="text-muted small mb-0">Manage incoming receipts (penerimaan) —
                                add, view and search.</p>
                        </div>
                    </div>
                </div>

                <!-- Inline Add Penerimaan Form -->
                <div class="card mb-3">
                    <div class="card-header py-2">
                        <strong class="small mb-0">Add Penerimaan</strong>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('penerimaan.store') }}" method="POST"
                            class="row g-3">
                            @csrf

                            <div class="col-md-4">
                                <label for="idpengadaan" class="form-label">Pilih Pengadaan</label>
                                <select name="idpengadaan" id="idpengadaan"
                                    class="form-select form-select-sm">
                                    <option value="">-- Pilih Pengadaan --</option>
                                    @foreach ($pengadaans as $p)
                                        <option value="{{ $p->idpengadaan }}"
                                            data-total="{{ $p->total_nilai ?? 0 }}">
                                            {{ 'ID ' . $p->idpengadaan . ' - ' . ($p->nama_vendor ?? ($p->vendor ?? '')) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="tgl_penerimaan" class="form-label">Tanggal
                                    Penerimaan</label>
                                <input type="date" name="tgl_penerimaan" id="tgl_penerimaan"
                                    class="form-control form-control-sm"
                                    value="{{ old('tgl_penerimaan', date('Y-m-d')) }}">
                            </div>

                            <div class="col-md-2">
                                <label for="status_penerimaan" class="form-label">Status</label>
                                <select name="status_penerimaan" id="status_penerimaan"
                                    class="form-select form-select-sm">
                                    <option value="0">Proses</option>
                                    <option value="1">Selesai</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="iduser" class="form-label">ID User</label>
                                <select name="iduser" id="iduser"
                                    class="form-select form-select-sm">
                                    <option value="">-- Pilih User --</option>
                                    @foreach ($users as $u)
                                        <option value="{{ $u->iduser }}">{{ $u->username }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 text-end mt-1">
                                <button type="submit" class="btn btn-primary btn-sm">Add
                                    Penerimaan</button>
                            </div>

                            {{-- Prediction preview (uses selected pengadaan total) --}}
                            <div class="col-12 mt-2">
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <label class="form-label small text-muted">Prediksi
                                            Subtotal</label>
                                        <div id="pred_subtotal"
                                            class="form-control form-control-sm">-</div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small text-muted">Prediksi PPN
                                            ({{ config('app.ppn_percent', 11) }}%)</label>
                                        <div id="pred_ppn" class="form-control form-control-sm">-
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small text-muted">Prediksi
                                            Total</label>
                                        <div id="pred_total" class="form-control form-control-sm">-
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Catatan</small>
                                        <div class="form-text">Preview dihitung di browser dari
                                            nilai pengadaan; klik Add untuk menyimpan.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show fs-sm"
                                        role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close"
                                            data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif
                            </div>

                        </form>
                    </div>
                </div>

                <!-- Search Card -->
                {{-- <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <form action="{{ url('/manage_pengadaan') }}" method="GET"
                            class="row g-2 align-items-end">
                            <div class="col-md-4">
                                <label for="q_no_bukti" class="form-label visually-hidden">No.
                                    Bukti</label>
                                <input type="text" name="no_bukti" id="q_no_bukti"
                                    class="form-control form-control-sm"
                                    placeholder="Search by No. Bukti"
                                    value="{{ request('no_bukti') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="q_supplier"
                                    class="form-label visually-hidden">Supplier</label>
                                <input type="text" name="supplier" id="q_supplier"
                                    class="form-control form-control-sm"
                                    placeholder="Search by Supplier"
                                    value="{{ request('supplier') }}">
                            </div>
                            <div class="col-md-2 d-grid">
                                <button type="submit"
                                    class="btn btn-primary btn-sm">Search</button>
                            </div>
                        </form>
                        <p class="mt-2 text-muted small mb-0">Use the fields above to filter
                            pengadaan records.</p>
                    </div>
                </div> --}}

                <!-- Penerimaan Table -->
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:50px">No</th>
                                        <th style="width:50px ">ID</th>
                                        <th style="width: 50px" class="text-center">ID Pengadaan
                                        </th>
                                        <th style="width:  " class="text-center">Tanggal</th>
                                        <th>Supplier</th>
                                        <th class="text-end">Total</th>
                                        <th>Status</th>
                                        <th>User</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($penerimaans ?? [] as $penerimaan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $penerimaan->idpenerimaan ?? ($penerimaan->id ?? '-') }}
                                            </td>
                                            <td>{{ $penerimaan->idpengadaan ?? '-' }}</td>
                                            <td class="text-center">
                                                {{ isset($penerimaan->tgl_penerimaan) ? \Carbon\Carbon::parse($penerimaan->tgl_penerimaan)->format('d M Y') : (isset($penerimaan->created_at) ? \Carbon\Carbon::parse($penerimaan->created_at)->format('d M Y') : '-') }}
                                            </td>
                                            <td>{{ $penerimaan->nama_vendor ?? ($penerimaan->vendor->nama_vendor ?? '-') }}
                                            </td>
                                            <td class="text-end">
                                                {{ isset($penerimaan->total_nilai) ? number_format($penerimaan->total_nilai, 0, ',', '.') : '-' }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge {{ ($penerimaan->status_penerimaan ?? ($penerimaan->status ?? '0')) == '1' ? 'bg-success' : 'bg-secondary' }}">{{ ($penerimaan->status_penerimaan ?? ($penerimaan->status ?? '0')) == '1' ? 'Selesai' : 'Proses' }}</span>
                                            </td>
                                            <td>{{ $penerimaan->username ?? ($penerimaan->user->username ?? '-') }}
                                            </td>
                                            <td>
                                                <a href="{{ url('/manage_penerimaan/' . ($penerimaan->idpenerimaan ?? ($penerimaan->id ?? '')) . '/edit') }}"
                                                    class="btn btn-sm btn-warning me-1">Edit</a>
                                                <a href="{{ url('/detail_penerimaan/' . ($penerimaan->idpenerimaan ?? ($penerimaan->id ?? ''))) }}"
                                                    class="btn btn-sm btn-info me-1">View</a>
                                                <form
                                                    action="{{ url('/manage_penerimaan/' . ($penerimaan->idpenerimaan ?? ($penerimaan->id ?? ''))) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Hapus penerimaan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-4">No
                                                penerimaan found</td>
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
    <script>
        (function() {
            const ppn = {{ config('app.ppn_percent', 11) }};
            const sel = document.getElementById('idpengadaan');
            const pred_sub = document.getElementById('pred_subtotal');
            const pred_ppn = document.getElementById('pred_ppn');
            const pred_total = document.getElementById('pred_total');

            function format(n) {
                return new Intl.NumberFormat('id-ID', {
                    maximumFractionDigits: 0
                }).format(n);
            }

            function update() {
                if (!sel) return;
                const opt = sel.options[sel.selectedIndex];
                const total = opt ? parseFloat(opt.dataset.total || 0) : 0;
                if (!opt || !opt.value) {
                    pred_sub.textContent = '-';
                    pred_ppn.textContent = '-';
                    pred_total.textContent = '-';
                    return;
                }
                const subtotal = total;
                const ppnval = Math.round(subtotal * ppn / 100);
                const totalval = subtotal + ppnval;
                pred_sub.textContent = format(subtotal);
                pred_ppn.textContent = format(ppnval);
                pred_total.textContent = format(totalval);
            }

            if (sel) {
                sel.addEventListener('change', update);
                update();
            }
        })();
    </script>
</body>
