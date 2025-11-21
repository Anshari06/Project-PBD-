@extends('layouts.app')

@section('title', 'Manage Penerimaan')

@section('content')
    {{-- head --}}
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <h2 class="mb-0">Penerimaan Management</h2>
                <p class="text-muted small mb-0">Manage incoming receipts (penerimaan) — add, view and
                    search.</p>
            </div>
        </div>
    </div>

    <!-- Inline Add Penerimaan Form -->
    <div class="card mb-3">
        <div class="card-header py-2">
            <strong class="small mb-0">Add Penerimaan</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('penerimaan.store') }}" method="POST" class="row g-3">
                @csrf
                @if ($errors->any())
                    <div class="col-12 small">
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="col-md-5">
                    <label for="idpengadaan" class="form-label">Pilih Pengadaan</label>
                    <select name="idpengadaan" id="idpengadaan" class="form-select form-select-sm">
                        <option value="">-- Pilih Pengadaan --</option>
                        @foreach ($pengadaans as $p)
                            <option value="{{ $p->idpengadaan }}"
                                data-total="{{ $p->total_nilai ?? 0 }}">
                                {{ 'ID ' . $p->idpengadaan . ' - ' . $p->status . ' - ' . $p->created_at }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <input type="hidden" name="iduser" value="{{ auth()->user()->id }}">

                <div class="col-md-3">
                    <label for="tgl_penerimaan" class="form-label">Tanggal Penerimaan</label>
                    <input type="date" name="tgl_penerimaan" id="tgl_penerimaan"
                        class="form-control form-control-sm"
                        value="{{ old('tgl_penerimaan', date('Y-m-d')) }}">
                </div>

                {{-- <div class="col-md-2">
                    <label for="status_penerimaan" class="form-label">Status</label>
                    <select name="status_penerimaan" id="status_penerimaan"
                        class="form-select form-select-sm">
                        <option value="O">In Process</option>
                        <option value="R">Return</option>
                        <option value="S">Selesai</option>
                    </select>
                </div> --}}

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
                                    <div class="form-text small text-muted item-sisa">Sisa: -</div>
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
                <button type="button" id="add-barang" class="btn btn-sm btn-secondary">Tambah
                    Barang</button>

                <hr>

                <div class="col-12 text-end mt-1">
                    <button type="submit" class="btn btn-primary btn-sm">Add Penerimaan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-12 mb-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show fs-sm" role="alert">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger alert-dismissible fade show fs-sm">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- Penerimaan Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th style="width:50px ">ID</th>
                            <th style="width: 50px" class="text-center">ID Pengadaan</th>
                            <th style="width:  " class="text-center">Waktu Penerimaan</th>
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
                                <td>{{ $penerimaan->idpenerimaan ?? ($penerimaan->id ?? '-') }}</td>
                                <td>{{ $penerimaan->idpengadaan ?? '-' }}</td>
                                <td class="text-center">
                                    {{ isset($penerimaan->tgl_penerimaan) ? \Carbon\Carbon::parse($penerimaan->tgl_penerimaan)->format('d M Y H:i') : (isset($penerimaan->created_at) ? \Carbon\Carbon::parse($penerimaan->created_at)->format('d M Y H:i') : '-') }}
                                </td>
                                <td>{{ $penerimaan->nama_vendor ?? ($penerimaan->vendor->nama_vendor ?? '-') }}
                                </td>
                                <td class="text-end">
                                    {{ isset($penerimaan->total_nilai) ? number_format($penerimaan->total_nilai, 0, ',', '.') : '-' }}
                                </td>
                                <td>
                                    @php
                                        $st = $penerimaan->status_penerimaan;

                                        switch ($st) {
                                            case 'O':
                                                $cls = 'bg-warning text-dark';
                                                $lbl = 'In Process';
                                                break;
                                            case 'S':
                                                $cls = 'bg-success';
                                                $lbl = 'Selesai';
                                                break;
                                            case 'R':
                                                $cls = 'bg-danger';
                                                $lbl = 'Return';
                                                break;
                                            default:
                                                $cls = 'bg-secondary';
                                                $lbl = $st;
                                                break;
                                        }
                                    @endphp
                                    <span class="badge {{ $cls }}">{{ $lbl }}</span>
                                </td>
                                <td>{{ $penerimaan->username ?? ($penerimaan->user->username ?? '-') }}
                                </td>
                                <td>

                                    <button type="button"
                                        class="btn btn-sm btn-primary me-1 btn-edit-popup"
                                        data-id="{{ $penerimaan->idpenerimaan ?? ($penerimaan->id ?? '') }}"
                                        data-status="{{ $penerimaan->status_penerimaan ?? ($penerimaan->status ?? '') }}">Set
                                        Status</button>
                                    <a href="{{ url('/detail_penerimaan/' . ($penerimaan->idpenerimaan ?? ($penerimaan->id ?? ''))) }}"
                                        class="btn btn-sm btn-info me-1">View</a>
                                    <form
                                        action="{{ url('/manage_penerimaan/' . ($penerimaan->idpenerimaan ?? ($penerimaan->id ?? ''))) }}"
                                        method="POST" class="d-inline"
                                        onsubmit="return confirm('Hapus penerimaan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">No penerimaan found</td>
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
            const sel = document.getElementById('idpengadaan');
            let currentItems = [];
            const itemsTable = document.getElementById('items_table');
            const addBtn = document.getElementById('add-barang');

            function formatNumber(n) {
                return new Intl.NumberFormat('id-ID').format(n);
            }

            function escapeHtml(str) {
                if (!str) return '';
                return String(str).replace(/[&<>"]+/g, function(c) {
                    return {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;'
                    } [c] || c;
                });
            }

            // Build option HTML from items array
            function buildOptions(items) {
                const base = '<option value="">-- Pilih Barang --</option>';
                const opts = items.map(i => {
                    const harga = i.harga ?? 0;
                    const sisa = i.sisa ?? 0;
                    return `<option value="${i.idbarang}" data-harga="${harga}" data-sisa="${sisa}">${i.nama}</option>`;
                }).join('');
                return base + opts;
            }

            // When pengadaan items arrive we store them so per-row sisa can be shown
            function updatePreviewFromItems(items) {
                currentItems = items || [];
            }

            // Fill item selects with provided items
            function populateItemSelects(items) {
                const tbody = itemsTable.querySelector('tbody');
                const selects = tbody.querySelectorAll('.idbarang');
                const opts = buildOptions(items);
                selects.forEach(s => {
                    // preserve current value if exists in new items
                    const cur = s.value;
                    s.innerHTML = opts;
                    if (cur) {
                        const found = Array.from(s.options).some(o => o.value === cur);
                        if (found) s.value = cur;
                    }
                    // update per-row sisa display based on selected option
                    const row = s.closest('.item-row');
                    const sisaEl = row?.querySelector('.item-sisa');
                    const selOpt = s.selectedOptions?.[0];
                    const sisaVal = selOpt ? Number(selOpt.dataset.sisa || 0) : null;
                    if (sisaEl) sisaEl.textContent = (sisaVal !== null) ? ('Sisa: ' + (
                        sisaVal ? formatNumber(sisaVal) : '0')) : 'Sisa: -';
                });
            }

            // Recalculate remaining 'sisa' per item across all rows and validate inputs
            function recalcSubtotalFromRows() {
                const rows = Array.from(itemsTable.querySelectorAll('.item-row'));

                // First, aggregate requested quantities per selected item id
                const requested = {};
                rows.forEach(row => {
                    const sel = row.querySelector('.idbarang');
                    const qtyEl = row.querySelector('.jumlah');
                    const id = sel?.value || '';
                    const qty = qtyEl ? parseInt((qtyEl.value || '').toString().replace(
                        /[^0-9-]/g, '')) || 0 : 0;
                    if (id) {
                        requested[id] = (requested[id] || 0) + qty;
                    }
                });

                let hasError = false;

                // Now, for each row compute remaining and validate
                rows.forEach(row => {
                    const select = row.querySelector('.idbarang');
                    const qtyEl = row.querySelector('.jumlah');
                    const sisaEl = row.querySelector('.item-sisa');
                    const opt = select?.selectedOptions?.[0];
                    const id = select?.value || '';

                    if (!opt || typeof opt.dataset.sisa === 'undefined') {
                        if (sisaEl) sisaEl.textContent = 'Sisa: -';
                        if (qtyEl) qtyEl.classList.remove('is-invalid');
                        return;
                    }

                    const initialSisa = Number(opt.dataset.sisa || 0);
                    const totalRequested = requested[id] || 0;
                    const remaining = initialSisa - totalRequested;

                    if (sisaEl) {
                        // If remaining negative, show negative with minus sign and red text
                        if (remaining < 0) {
                            sisaEl.textContent = 'Sisa: -' + formatNumber(Math.abs(
                                remaining));
                            sisaEl.classList.add('text-danger');
                        } else {
                            sisaEl.textContent = 'Sisa: ' + (remaining ? formatNumber(
                                remaining) : '0');
                            sisaEl.classList.remove('text-danger');
                        }
                    }

                    if (qtyEl) {
                        if (totalRequested > initialSisa) {
                            qtyEl.classList.add('is-invalid');
                            hasError = true;
                        } else {
                            qtyEl.classList.remove('is-invalid');
                        }
                    }
                });

                // disable submit button if any invalid
                const formEl = itemsTable.closest('form');
                const submitBtn = formEl ? formEl.querySelector('button[type="submit"]') : null;
                if (submitBtn) submitBtn.disabled = hasError;
            }


            // Fetch items for pengadaan via AJAX and populate selects + preview
            async function fetchPengadaanItems(id) {
                if (!id) {
                    // reset per-row sisa displays and stored items
                    currentItems = [];
                    itemsTable.querySelectorAll('.item-sisa').forEach(el => el.textContent =
                        'Sisa: -');
                    const summaryEl = document.getElementById('pred_items');
                    if (summaryEl) summaryEl.innerHTML = '-';
                    return;
                }
                try {
                    const res = await fetch(`/pengadaan/${id}/items`);
                    if (!res.ok) throw new Error('Network response was not ok');
                    const json = await res.json();
                    const items = json.items || [];
                    const total = json.total ?? 0;
                    populateItemSelects(items);
                    updatePreviewFromItems(items);
                    // after populating selects, recalc subtotal from current jumlah inputs
                    recalcSubtotalFromRows();
                } catch (err) {
                    console.error('Failed to fetch pengadaan items', err);
                }
            }

            // Event bindings
            sel?.addEventListener('change', (e) => {
                const id = e.target.value;
                fetchPengadaanItems(id);
            });

            // Delegate input/change on table to recalc subtotal
            itemsTable?.addEventListener('input', (e) => {
                if (e.target.classList.contains('jumlah')) recalcSubtotalFromRows();
            });
            itemsTable?.addEventListener('change', (e) => {
                if (e.target.classList.contains('idbarang')) recalcSubtotalFromRows();
            });

            // Add row
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
                // if a pengadaan is selected and we have fetched items, populate options and sisa on the new row
                if (currentItems && currentItems.length) {
                    populateItemSelects(currentItems);
                }
                recalcSubtotalFromRows();
            });

            // Remove row (delegated)
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
                    recalcSubtotalFromRows();
                }
            });

            // initial: if a pengadaan is preselected, fetch its items; otherwise leave server-rendered barang list
            if (sel && sel.value) fetchPengadaanItems(sel.value);

            // Edit Status modal handler
            const editButtons = document.querySelectorAll('.btn-edit-popup');
            const editForm = document.getElementById('editStatusForm');
            const editSelect = document.getElementById('edit_status_penerimaan');
            const editModalEl = document.getElementById('editStatusModal');
            let editModal = null;
            if (editModalEl && typeof bootstrap !== 'undefined') {
                editModal = new bootstrap.Modal(editModalEl);
            }

            editButtons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const id = btn.dataset.id;
                    const status = btn.dataset.status || '';
                    if (!editForm) return;
                    // set form action to the resource update endpoint (PUT)
                    editForm.action = '/manage_penerimaan/' +
                        encodeURIComponent(id);
                    // set method override
                    const methodInput = editForm.querySelector(
                        'input[name=_method]');
                    if (methodInput) methodInput.value = 'PUT';
                    // set select value
                    if (editSelect) editSelect.value = status;
                    // show modal
                    if (editModal) editModal.show();
                });
            });
        });
    </script>
@endsection

<!-- Edit Status Modal -->
<div class="modal fade" id="editStatusModal" tabindex="-1" aria-labelledby="editStatusModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <form id="editStatusForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStatusModalLabel">Set Status Penerimaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="edit_status_penerimaan"
                            class="form-label small">Status</label>
                        <select id="edit_status_penerimaan" name="status_penerimaan"
                            class="form-select form-select-sm">
                            <option value="O">In Process</option>
                            <option value="S">Selesai</option>
                            <option value="R">Return</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
