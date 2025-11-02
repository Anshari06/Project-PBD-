<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
        crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <title>Manage Vendor</title>
</head>

<body class="d-flex" style="min-height: 100vh; overflow: hidden;">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    <x-sidebar></x-sidebar>

    <main class="flex-grow-1 d-flex flex-column" style="height: 100vh; overflow: hidden;">
        <x-header>Manage Vendor</x-header>

        <div class="flex-grow-1" style="overflow-y: auto;">
            <div class="container-fluid p-3">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h2 class="mb-0">Vendor Management</h2>
                            <p class="text-muted small mb-0">Add and search vendors here.</p>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header py-2">
                        <strong class="small mb-0">Add Vendor</strong>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/vendor') }}" method="POST"
                            class="row g-2 align-items-end">
                            @csrf
                            <div class="col-md-4">
                                <label for="nama_vendor" class="visually-hidden">Nama Vendor</label>
                                <input type="text" name="nama_vendor" id="nama_vendor"
                                    class="form-control form-control-sm" placeholder="Nama Vendor"
                                    required>
                            </div>
                            <div class="col-md-3">
                                <label for="kontak" class="visually-hidden">Kontak</label>
                                <input type="text" name="kontak" id="kontak"
                                    class="form-control form-control-sm"
                                    placeholder="Kontak / Telepon">
                            </div>
                            <div class="col-md-3">
                                <label for="alamat" class="visually-hidden">Alamat</label>
                                <input type="text" name="alamat" id="alamat"
                                    class="form-control form-control-sm" placeholder="Alamat">
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="submit" class="btn btn-primary btn-sm w-100">Add
                                    Vendor</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card my-5">
                    <div class="card-header py-2">
                        <strong class="small mb-0">Cek Status Vendor</strong>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/manage_vendor') }}" method="GET"
                            class="row g-2 align-items-end">
                            <div class="col-md-4">
                                <label for="vendor_id" class="form-label">Pilih Vendor</label>
                                <select name="vendor_id" id="vendor_id" class="form-select form-select-sm">
                                    <option value="">-- Pilih Vendor --</option>
                                    @foreach($allVendors ?? collect() as $v)
                                        <option value="{{ $v->idvendor }}" {{ request('vendor_id') == $v->idvendor ? 'selected' : '' }}>{{ $v->idvendor }} - {{ $v->nama_vendor }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 d-grid">
                                <button type="submit" class="btn btn-primary btn-sm">Cek</button>
                            </div>
                            <div class="row-md-6">
                                @if (isset($vendorStatus))
                                    <div class="alert alert-info mb-0" role="alert">
                                        Status vendor (ID: {{ request('vendor_id') }}):
                                        <strong>{{ $vendorStatus }}</strong>
                                    </div>
                                @else
                                    <div class="text-muted small">Masukkan ID vendor dan tekan
                                        <strong>Cek</strong> untuk melihat status.</div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                {{-- tabel data vendor --}}
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:70px">#</th>
                                        <th style="width:70px">ID</th>
                                        <th>Nama Vendor</th>
                                        <th>Badan Hukum</th>
                                        <th>Status</th>
                                        <th style="width:160px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($allVendors ?? collect() as $vendor)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $vendor->idvendor ?? '-' }}</td>
                                            <td>{{ $vendor->nama_vendor ?? '-' }}</td>
                                            <td>{{ $vendor->badan_hukum == 1 ? 'aktif' : 'non-aktif' }}
                                            </td>
                                            <td>{{ $vendor->status == 1 ? 'aktif' : 'nonaktif' }}
                                            </td>
                                            <td>
                                                <a href="{{ url('/vendor/' . ($vendor->idvendor ?? $vendor->id) . '/edit') }}"
                                                    class="btn btn-sm btn-warning me-1">Edit</a>
                                                <form
                                                    action="{{ url('/vendor/' . ($vendor->idvendor ?? $vendor->id)) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Hapus vendor ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">No vendors
                                                found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- <div class="card m-3">
                    <div class="card-header py-2">
                        <strong class="small mb-0">Search Vendors</strong>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/vendor') }}" method="GET"
                            class="row g-2 align-items-end">
                            <div class="col-md-6">
                                <label for="q" class="form-label">Search by name</label>
                                <input type="text" name="q" id="q"
                                    class="form-control form-control-sm"
                                    value="{{ request('q') }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit"
                                    class="btn btn-primary btn-sm w-100">Search</button>
                            </div>
                        </form>

                        <div class="table-responsive mt-4">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th style="width:70px">#</th>
                                        <th style="width:70px">ID</th>
                                        <th>Nama Vendor</th>
                                        <th>Kontak</th>
                                        <th>Alamat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($searchResults ?? collect() as $vendor)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $vendor->idvendor ?? '-' }}</td>
                                            <td>{{ $vendor->nama ?? '-' }}</td>
                                            <td>{{ $vendor->kontak ?? '-' }}</td>
                                            <td>{{ $vendor->alamat ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">No results
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </main>
</body>
