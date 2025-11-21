@extends('layouts.app')

@section('title', 'Manage Penjualan')

@section('content')
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <h2 class="mb-0">Penjualan Management</h2>
                <p class="text-muted small mb-0">Manage sales records — add, view, search and delete
                    penjualan.</p>
            </div>
        </div>
    </div>

    <div>
        <a href="{{ url('/detail_penjualan') }}" class="btn btn-info text-light btn-sm mb-3">Go to Detail
            Penjualan</a>
    </div>

    <!-- Inline Add Penjualan Form -->
    <div class="card mb-3">
        <div class="card-header py-2">
            <strong class="small mb-0">Add Penjualan</strong>
        </div>
        <div class="card-body">
            <form action="{{ url('/manage_penjualan') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-3">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal"
                        class="form-control form-control-sm" required
                        value="{{ old('tanggal', date('Y-m-d')) }}">
                </div>

                <div class="col-md-3">
                    <label for="no_nota" class="form-label">No. Nota</label>
                    <input type="text" name="no_nota" id="no_nota"
                        class="form-control form-control-sm" placeholder="No. Nota" required
                        value="{{ old('no_nota') }}">
                </div>

                <div class="col-md-4">
                    <label for="pelanggan" class="form-label">Pelanggan</label>
                    <input type="text" name="pelanggan" id="pelanggan"
                        class="form-control form-control-sm" placeholder="Nama Pelanggan"
                        value="{{ old('pelanggan') }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Items</label>
                    <table class="table table-sm mb-0" id="items_table_sales">
                        <thead>
                            <tr>
                                <th style="width:60%">Barang</th>
                                <th style="width:20%">Jumlah</th>
                                <th style="width:20%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="item-row">
                                <td>
                                    <select name="idbarang[]"
                                        class="form-select form-select-sm idbarang">
                                        <option value="">-- Pilih Barang --</option>
                                        @if (!empty($barangs))
                                            @foreach ($barangs as $b)
                                                <option value="{{ $b->idbarang }}"
                                                    data-harga="{{ $b->harga }}">{{ $b->nama }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <input name="jumlah[]"
                                        class="form-control form-control-sm jumlah text-end"
                                        inputmode="numeric">
                                </td>
                                <td>
                                    <button type="button"
                                        class="btn btn-sm btn-danger remove-row">Remove</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-12">
                    <button type="button" id="add-barang-sales" class="btn btn-sm btn-secondary">Tambah
                        Barang</button>
                </div>

                <div class="col-12 text-end mt-1">
                    <button type="submit" class="btn btn-primary btn-sm">Add Penjualan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @if (session('success'))
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show fs-sm" role="alert">
                    {{ session('success') }}
                </div>
            </div>
        @elseif (session('error'))
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible fade show fs-sm" role="alert">
                    {{ session('error') }}
                </div>
            </div>
        @endif
    </div>

    <!-- Penjualan Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th style="width:70px">#</th>
                            <th style="width:70px">ID</th>
                            <th scope="col" class="text-center">Tanggal</th>
                            <th style="width:150px">Subtotal</th>
                            <th>User</th>
                            <th style="width:140px">Total Nilai</th>
                            <th style="width:180px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penjualans ?? [] as $penjualan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $penjualan->idpenjualan }}</td>
                                <td scope="col" class="text-center">
                                    {{ $penjualan->created_at ?? '-' }}</td>
                                <td>{{ $penjualan->subtotal_nilai ?? '-' }}</td>
                                <td>{{ $penjualan->user->username ?? '-' }}</td>
                                <td>{{ isset($penjualan->total_nilai) ? number_format($penjualan->total_nilai, 0, ',', '.') : '-' }}
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
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">No penjualan found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const itemsTable = document.getElementById('items_table_sales');
            const addBtn = document.getElementById('add-barang-sales');

            addBtn?.addEventListener('click', () => {
                const tbody = itemsTable.querySelector('tbody');
                const template = tbody.querySelector('.item-row');
                const clone = template.cloneNode(true);
                clone.querySelectorAll('select, input').forEach(el => {
                    if (el.tagName.toLowerCase() === 'select') el
                        .selectedIndex = 0;
                    else el.value = '';
                });
                tbody.appendChild(clone);
            });

            itemsTable?.addEventListener('click', (e) => {
                if (e.target.classList.contains('remove-row')) {
                    const tbody = itemsTable.querySelector('tbody');
                    const rows = tbody.querySelectorAll('.item-row');
                    const row = e.target.closest('.item-row');
                    if (rows.length > 1) row.remove();
                    else {
                        row.querySelectorAll('select, input').forEach(el => {
                            if (el.tagName.toLowerCase() === 'select') el
                                .selectedIndex = 0;
                            else el.value = '';
                        });
                    }
                }
            });
        });
    </script>
@endsection
