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
    <title>Manage Pengadaan</title>
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
        <x-header>Manage Pengadaan</x-header>

        {{-- Content Area with Scroll --}}
        <div class="flex-grow-1" style="overflow-y: auto;">

            <div class="container-fluid p-3">
                {{-- head --}}
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h2 class="mb-0">Pengadaan Management</h2>
                            <p class="text-muted small mb-0">Manage procurement requests (pengadaan)
                                — add, view and search.</p>
                        </div>
                    </div>
                </div>

                <!-- Inline Add Pengadaan Form -->
                <div class="card mb-3">
                    <div class="card-header py-2">
                        <strong class="small mb-0">Add Pengadaan</strong>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/manage_pengadaan') }}" method="POST"
                            class="row g-2">
                            @csrf

                            {{-- idpengadaan is auto-increment; created_at handled by DB --}}

                            <div class="col-md-4">
                                <label for="idvendor" class="form-label">Vendor</label>
                                @if (isset($vendors) && count($vendors) > 0)
                                    <select name="idvendor" id="idvendor"
                                        class="form-select form-select-sm">
                                        <option value="">-- Pilih Vendor --</option>
                                        @foreach ($vendors as $v)
                                            <option value="{{ $v->idvendor }}"
                                                {{ old('idvendor') == $v->idvendor ? 'selected' : '' }}>
                                                {{ $v->nama_vendor ?? ($v->name ?? 'Vendor ' . $v->idvendor) }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="number" name="idvendor" id="idvendor"
                                        class="form-control form-control-sm" placeholder="ID Vendor"
                                        value="{{ old('idvendor') }}">
                                @endif
                            </div>

                            <div class="col-md-2">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status"
                                    class="form-select form-select-sm">
                                    <option value="">-- Status --</option>
                                    <option value="1"
                                        {{ old('status') == '1' ? 'selected' : '' }}>Selesai
                                    </option>
                                    <option value="0"
                                        {{ old('status') == '0' ? 'selected' : '' }}>Proses</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="ppn" class="form-label">PPN</label>
                                <input inputmode="numeric" pattern="[0-9]*" name="ppn" id="ppn"
                                    class="form-control form-control-sm text-end"
                                    value="{{ old('ppn', 0) }}">
                            </div>

                            <div class="col-md-2">
                                <label for="subtotal_nilai" class="form-label">Subtotal</label>
                                <input inputmode="numeric" pattern="[0-9]*" name="subtotal_nilai" id="subtotal_nilai"
                                    class="form-control form-control-sm text-end"
                                    value="{{ old('subtotal_nilai') }}">
                            </div>

                            <div class="col-md-2">
                                <label for="total_nilai" class="form-label">Total</label>
                                <input type="number" inputmode="numeric" name="total_nilai" id="total_nilai"
                                    class="form-control form-control-sm text-end"
                                    value="{{ old('total_nilai') }}">
                            </div>

                            {{-- set iduser from auth if available; fallback to number input for admin testing --}}
                            @if (auth()->check())
                                <input type="hidden" name="iduser" value="{{ auth()->id() }}">
                            @else
                                <div class="col-md-2">
                                    <label for="iduser" class="form-label">ID User</label>
                                    <input type="number" name="iduser" id="iduser"
                                        class="form-control form-control-sm"
                                        value="{{ old('iduser') }}">
                                </div>
                            @endif

                            <div class="col-12 text-end mt-1">
                                <button type="submit" class="btn btn-primary btn-sm">Add
                                    Pengadaan</button>
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

                <!-- Pengadaan Table -->
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0 gap-1">
                                <thead>
                                    <tr class="gap-2">
                                        <th scope="col" style="width:70px">No</th>
                                        <th style="">ID</th>
                                        <th scope="col" class="text-center">Tanggal</th>
                                        <th scope="col" style="width:150px">Supplier</th>
                                        <th scope="col" class="text-end">PPN</th>
                                        <th scope="col" class="text-end">Subtotal</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">User</th>
                                        <th scope="col-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pengadaans ?? [] as $pengadaan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $pengadaan->idpengadaan ?? ($pengadaan->id ?? '-') }}
                                            </td>
                                            <td>{{ isset($pengadaan->tanggal) ? \Carbon\Carbon::parse($pengadaan->tanggal)->format('d M Y') : $pengadaan->created_at ?? '-' }}
                                            </td>
                                            <td>{{ $pengadaan->vendor->nama_vendor ?? '-' }}</td>
                                            <td class="text-end">
                                                {{ isset($pengadaan->ppn) ? number_format($pengadaan->ppn, 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="text-end">
                                                {{ isset($pengadaan->subtotal_nilai) ? number_format($pengadaan->subtotal_nilai, 0, ',', '.') : '-' }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge {{ ($pengadaan->status ?? '0') == '1' ? 'bg-success' : 'bg-secondary' }}">{{ ($pengadaan->status ?? '0') == '1' ? 'Selesai' : 'Proses' }}</span>
                                            </td>
                                            <td>{{ $pengadaan->user->username ?? '-' }}</td>
                                            <td>
                                                <a href="{{ url('/manage_pengadaan/' . ($pengadaan->idpengadaan ?? ($pengadaan->id ?? '')) . '/edit') }}"
                                                    class="btn btn-sm btn-warning me-1">Edit</a>
                                                <a href="{{ url('/manage_pengadaan/' . ($pengadaan->idpengadaan ?? ($pengadaan->id ?? ''))) }}"
                                                    class="btn btn-sm btn-info me-1">View</a>
                                                <form
                                                    action="{{ url('/manage_pengadaan/' . ($pengadaan->idpengadaan ?? ($pengadaan->id ?? ''))) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Hapus pengadaan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">No
                                                pengadaan found</td>
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
