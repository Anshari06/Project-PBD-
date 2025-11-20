@extends('layouts.app')

@section('title', 'Manage Pengadaan')

@section('content')
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <h2 class="mb-0">Pengadaan Management</h2>
                <p class="text-muted small mb-0">Manage procurement requests — add, view and search.</p>
            </div>
        </div>
    </div>

    <!-- Inline Add Pengadaan Form -->
    <div class="card mb-3">
        <div class="card-header py-2">
            <strong class="small mb-0">Add Pengadaan</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('pengadaan.store') }}" method="POST" class="row g-3">
                @csrf

                <div class="col-md-4">
                    <label class="form-label">Vendor</label>
                    <select name="idvendor" class="form-select form-select-sm">
                        <option value="">-- Pilih Vendor --</option>
                        @foreach ($vendors as $v)
                            <option value="{{ $v->idvendor }}">
                                {{ $v->nama_vendor ?? 'Vendor ' . $v->idvendor }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Items</label>
                    <table class="table table-sm mb-0" id="items_table">
                        <thead>
                            <tr>
                                <th style="width:60%">Barang</th>
                                <th style="width:20%">Jumlah</th>
                                <th style="width:20%"> </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="item-row">
                                <td>
                                    <select name="idbarang[]"
                                        class="form-select form-select-sm idbarang">
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach ($barangs as $b)
                                            <option value="{{ $b->idbarang }}"
                                                data-harga="{{ $b->harga }}">{{ $b->nama }}
                                            </option>
                                        @endforeach
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
                        <tfoot>
                            <tr>
                                <td colspan="3">
                                    <button type="button" id="add_row"
                                        class="btn btn-sm btn-secondary">Tambah Item</button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="P">Proses</option>
                        <option value="O">Sebagian</option>
                        <option value="S">Selesai</option>
                        <option value="B">Batal</option>
                    </select>
                </div> --}}

                <div class="col-md-2">
                    <label class="form-label">User</label>
                    <div class="form-control form-control-sm">
                        {{ auth()->user()->username }}
                    </div>
                    <input type="hidden" name="iduser" value="{{ auth()->id() }}">
                </div>

                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary btn-sm mt-2">Add Pengadaan</button>
                </div>

                <!-- Preview -->
                <div class="col-12 mt-3">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="small text-muted">Prediksi Subtotal</label>
                            <div id="pred_subtotal" class="form-control form-control-sm">-</div>
                        </div>
                        <div class="col-md-3">
                            <label class="small text-muted">Prediksi PPN
                                ({{ config('app.ppn_percent', 11) }}%)</label>
                            <div id="pred_ppn" class="form-control form-control-sm">-</div>
                        </div>
                        <div class="col-md-3">
                            <label class="small text-muted">Prediksi Total</label>
                            <div id="pred_total" class="form-control form-control-sm">-</div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th class="text-center">Tanggal</th>
                            <th>Supplier</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-end">PPN</th>
                            <th class="text-end">Total</th>
                            <th>Status</th>
                            <th>User</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengadaans as $p)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $p->idpengadaan }}</td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($p->created_at)->format('d M Y') }}</td>
                                <td>{{ $p->nama_vendor }}</td>
                                <td class="text-end">
                                    {{ number_format($p->subtotal_nilai, 0, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($p->ppn, 0, ',', '.') }}%</td>
                                <td class="text-end">{{ number_format($p->total_nilai, 0, ',', '.') }}
                                </td>
                                <td>
                                    @php
                                        $st = $p->status ?? 'pengajuan';
                                        switch ($st) {
                                            case 'P':
                                                $cls = 'bg-primary';
                                                $lbl = 'Pending';
                                                break;
                                            case 'O':
                                                $cls = 'bg-warning text-dark';
                                                $lbl = 'On-Progress';
                                                break;
                                            case 'S':
                                                $cls = 'bg-success';
                                                $lbl = 'Selesai';
                                                break;
                                            case 'B':
                                                $cls = 'bg-danger';
                                                $lbl = 'Batal';
                                                break;
                                            default:
                                                $cls = 'bg-secondary';
                                                $lbl = $st;
                                                break;
                                        }
                                    @endphp
                                    <span class="badge {{ $cls }}">{{ $lbl }}</span>
                                </td>
                                <td>{{ $p->username }}</td>
                                <td>
                                    <a href="{{ url('/manage_pengadaan/' . $p->idpengadaan . '/edit') }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <a href="{{ url('/detail_pengadaan/' . $p->idpengadaan) }}"
                                        class="btn btn-info btn-sm">View</a>

                                    <form action="{{ route('pengadaan.destroy', $p->idpengadaan) }}"
                                        method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Hapus?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-3">No Data</td>
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
        (function() {
            function numberFormat(n) {
                return new Intl.NumberFormat('id-ID').format(n);
            }

            const ppnPercent = {{ config('app.ppn_percent', 11) }};
            const table = document.getElementById('items_table');
            const predSubtotal = document.getElementById('pred_subtotal');
            const predPpn = document.getElementById('pred_ppn');
            const predTotal = document.getElementById('pred_total');

            function computeAll() {
                let subtotal = 0;
                document.querySelectorAll('#items_table tbody tr').forEach(function(row) {
                    const select = row.querySelector('.idbarang');
                    const jumlahEl = row.querySelector('.jumlah');
                    const harga = parseFloat(select?.selectedOptions[0]?.dataset?.harga ||
                        0) || 0;
                    const jumlah = parseFloat(jumlahEl?.value || 0) || 0;
                    subtotal += harga * jumlah;
                });
                const ppn = Math.round(subtotal * (ppnPercent / 100));
                const total = Math.round(subtotal + ppn);

                predSubtotal.textContent = subtotal ? numberFormat(subtotal) : '-';
                predPpn.textContent = ppn ? numberFormat(ppn) : '-';
                predTotal.textContent = total ? numberFormat(total) : '-';
            }

            // add/remove row handlers
            document.getElementById('add_row').addEventListener('click', function() {
                const tbody = table.querySelector('tbody');
                const newRow = tbody.querySelector('tr.item-row').cloneNode(true);
                newRow.querySelectorAll('select, input').forEach(function(el) {
                    if (el.tagName === 'INPUT') el.value = '';
                    if (el.tagName === 'SELECT') el.selectedIndex = 0;
                });
                tbody.appendChild(newRow);
                attachRowEvents(newRow);
            });

            function attachRowEvents(row) {
                const select = row.querySelector('.idbarang');
                const jumlah = row.querySelector('.jumlah');
                const remove = row.querySelector('.remove-row');

                if (select) select.addEventListener('change', computeAll);
                if (jumlah) jumlah.addEventListener('input', computeAll);
                if (remove) remove.addEventListener('click', function() {
                    const tbody = table.querySelector('tbody');
                    if (tbody.querySelectorAll('tr').length > 1) {
                        row.remove();
                        computeAll();
                    } else {
                        // clear fields if only one row
                        select.selectedIndex = 0;
                        jumlah.value = '';
                        computeAll();
                    }
                });
            }

            // attach to initial row
            attachRowEvents(table.querySelector('tbody tr'));
        })();
    </script>
@endsection
