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
    <main class="flex-grow-1 d-flex flex-column" style="height: 100vh; overflow: hidden;">
        {{-- Header Fixed --}}
        <x-header>Manage Satuan</x-header>

        {{-- Content Area with Scroll --}}
        <div class="flex-grow-1" style="overflow-y: auto;">

            <div class="container-fluid p-3">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h2 class="mb-0">Satuan Management</h2>
                            <p class="text-muted small mb-0">Here you can manage items (Satuan) of
                                the system.</p>
                        </div>
                    </div>
                </div>

                <!-- Inline Add User Form (polished) -->
                <div class="card mb-3">
                    <div class="card-header py-2">
                        <strong class="small mb-0">Add Satuan</strong>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('/manage_satuan') }}" method="POST"
                            class="row g-2 align-items-end">
                            @csrf
                            <div class="col-md-4">
                                <label for="namasatuan" class="visually-hidden">Nama satuan</label>
                                <input type="text" name="namasatuan" id="namasatuan"
                                    class="form-control form-control-sm" placeholder="Nama Barang"
                                    required autofocus value="{{ old('namabarang') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="harga" class="visually-hidden">Harga</label>
                                <input type="number" name="harga" id="harga"
                                    class="form-control form-control-sm" placeholder="Harga"
                                    required value="{{ old('harga') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="stok" class="visually-hidden">Stok</label>
                                <input type="number" name="stok" id="stok"
                                    class="form-control form-control-sm" placeholder="Stok"
                                    value="{{ old('stok') }}">
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="submit" class="btn btn-primary btn-sm w-100">Add
                                    Barang</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Satuan Table -->
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 100px">Number</th>
                                        <th scope="col">ID</th>
                                        <th scope="col">Satuan</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Jenis</th>
                                        <th scope="col" style="width:180px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($satuans as $satuan)
                                        <tr>
                                            <th scope="row" style="text-center">
                                                {{ $loop->iteration }}</td>
                                            <td>{{ $satuan->idsatuan }}</td>
                                            <td>{{ $satuan->nama_satuan ?? '-' }}</td>
                                            <td>{{ $satuan->status == 1 ? 'Aktif' : 'Non-aktif' }}
                                            </td>
                                            <td>{{ $satuan->jenis ?? '-' }}</td>
                                            <td>
                                                <a href="{{ url('/manage_satuan/' . ($satuan->idsatuan ?? $satuan->id) . '/edit') }}"
                                                    class="btn btn-sm btn-warning me-1">Edit</a>
                                                <a href="{{ url('/manage_satuan/' . ($satuan->idsatuan ?? $satuan->id)) }}"
                                                    class="btn btn-sm btn-info me-1">View</a>
                                                <form
                                                    action="{{ url('/manage_satuan/' . ($satuan->idsatuan ?? $satuan->id)) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Hapus barang ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">No barang
                                                found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Cari data satuan by ID --}}
                <div class="card mt-4">
                    <div class="card-header p-2">
                        <strong class="small mb-0">Cari Data Satuan by ID</strong>
                    </div>
                    <div class="card-body">
                        <form action=" {{ url('/manage_satuan') }} " method="GET" class="row g-2 align-items-end">
                            @csrf
                            <div class="col-md-4">
                                <label for="id_satuan" class="form-label">ID Satuan:</label>
                                <select name="idsatuan" id="idsatuan"
                                    class="form-select form-select-sm">
                                    <option value="" selected>Pilih ID Satuan</option>
                                    @foreach ($dataSatuans as $dataSatuan)
                                        <option value="{{ $dataSatuan->idsatuan }}">
                                            {{ $dataSatuan->idsatuan }} - {{ $dataSatuan->nama_satuan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit"
                                    class="btn btn-primary btn-sm w-100">Search</button>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive px-3 pb-3">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ID Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Nama Satuan</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($satuanByID))
                                    @if (!empty($satuanByID))
                                        @foreach ($satuanByID as $satuan)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $satuan->idbarang }}</td>
                                                <td>{{ $satuan->nama }}</td>
                                                <td>{{ $satuan->nama_satuan }}</td>
                                                <td>{{ $satuan->harga }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center py-3">
                                                Tidak ada data dengan ID tersebut.
                                            </td>
                                        </tr>
                                    @endif
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center py-3">
                                            Silakan masukkan ID Satuan untuk mencari data.
                                        </td>
                                    </tr>
                                @endif
                            </tbody>

                        </table>
                    </div>
                </div>

            </div>
        </div>
    </main>
</body>
