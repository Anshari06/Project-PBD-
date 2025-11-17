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

                <div class="col-md-5">
                    <label for="idpengadaan" class="form-label">Pilih Pengadaan</label>
                    <select name="idpengadaan" id="idpengadaan" class="form-select form-select-sm">
                        <option value="">-- Pilih Pengadaan --</option>
                        @foreach ($pengadaans as $p)
                            <option value="{{ $p->idpengadaan }}"
                                data-total="{{ $p->total_nilai ?? 0 }}">
                                {{ 'ID ' . $p->idpengadaan . ' - ' . $p->nama_vendor . ' - ' . $p->status . ' - ' . $p->created_at }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <input type="hidden" name="iduser" value="{{ auth()->user()->id }}">

                <div class="col-md-4">
                    <label for="idbarang" class="form-label">Pilih Barang</label>
                    <select name="idbarang" id="idbarang" class="form-select form-select-sm">
                        @foreach ($barangs as $b)
                            <option value="{{ $b->idbarang }}">{{ $b->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="jumlah_terima" class="form-label">Jumlah Terima</label>
                    <input type="numeric" name="jumlah_terima" class="form-control form-control-sm">
                </div>

                <div class="col-md-3">
                    <label for="tgl_penerimaan" class="form-label">Tanggal Penerimaan</label>
                    <input type="date" name="tgl_penerimaan" id="tgl_penerimaan"
                        class="form-control form-control-sm"
                        value="{{ old('tgl_penerimaan', date('Y-m-d')) }}">
                </div>

                <div class="col-md-2">
                    <label for="status_penerimaan" class="form-label">Status</label>
                    <select name="status_penerimaan" id="status_penerimaan"
                        class="form-select form-select-sm">
                        <option value="O">In Process</option>
                        <option value="R">Return</option>
                        <option value="S">Selesai</option>
                    </select>
                </div>

                <div class="col-12 text-end mt-1">
                    <button type="submit" class="btn btn-primary btn-sm">Add Penerimaan</button>
                </div>

                {{-- Prediction preview (uses selected pengadaan total) --}}
                {{-- <div class="col-12 mt-2">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Prediksi Subtotal</label>
                            <div id="pred_subtotal" class="form-control form-control-sm">-</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Prediksi PPN
                                ({{ config('app.ppn_percent', 11) }}%)</label>
                            <div id="pred_ppn" class="form-control form-control-sm">-</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Prediksi Total</label>
                            <div id="pred_total" class="form-control form-control-sm">-</div>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Catatan</small>
                            <div class="form-text">Preview dihitung di browser dari nilai pengadaan;
                                klik Add untuk menyimpan.</div>
                        </div>
                    </div>
                </div> --}}

                <div class="row">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show fs-sm"
                            role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>

            </form>
        </div>
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
                                <td>{{ $penerimaan->idpenerimaan ?? ($penerimaan->id ?? '-') }}</td>
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
                                    @php
                                        $st =
                                            $penerimaan->status_penerimaan ??
                                            ($penerimaan->status ?? 'pengajuan');
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
@endsection
