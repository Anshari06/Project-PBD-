@extends('layouts.app')
@section('title', 'Manage Kartu Stok')

@section('content')
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <h2 class="mb-0">Kartu Stok Management</h2>
                <p class="text-muted small mb-0">Manage stock cards — view and search.</p>
            </div>
        </div>
    </div>

    <!-- Kartu Stok Table -->
    @php
        $ks_collection = collect($kartu_stoks ?? []);
        // For each item name, take the stock value from the latest record (by created_at)
        $byName = $ks_collection
            ->groupBy('nama')
            ->map(function ($group) {
                // try to sort by created_at if available, otherwise use collection order
                if ($group->first() && isset($group->first()->created_at)) {
                    $last = $group->sortBy('created_at')->last();
                } else {
                    $last = $group->last();
                }
                return $last ? $last->stock ?? 0 : 0;
            })
            ->sortDesc();
    @endphp

    <!-- Summary per Barang (colored) -->
    @php
        $palettes = ['primary', 'success', 'warning', 'danger', 'info', 'secondary', 'dark'];
    @endphp
    <div class="row g-3 mb-3">
        @forelse($byName as $name => $total)
            @php $color = $palettes[$loop->index % count($palettes)]; @endphp
            <div class="col-6 col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="d-flex">
                        <div class="bg-{{ $color }}"
                            style="width:6px;border-top-left-radius:.375rem;border-bottom-left-radius:.375rem;">
                        </div>
                        <div class="card-body d-flex align-items-center flex-grow-1">
                            <i class="bi bi-box fs-1 text-{{ $color }} me-3"></i>
                            <div>
                                <div class="text-muted small">{{ $name ?? '-' }}</div>
                                <div class="fs-5 fw-bold">{{ number_format($total, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-secondary">No stock items available</div>
            </div>
        @endforelse
    </div>

    <div class="card">
        <div class="card-header py-2">
            <strong class="small mb-0">Kartu Stok List</strong>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th scope="col">ID Kartu Stok</th>
                        <th scope="col">Barang</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Jenis Transaksi</th>
                        <th scope="col">Jumlah Masuk</th>
                        <th scope="col">Jumlah Keluar</th>
                        <th scope="col">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kartu_stoks as $ks)
                        <tr>
                            <td>{{ $ks->idkartu_stock }}</td>
                            <td>{{ $ks->nama }}</td>
                            <td>{{ $ks->created_at }}</td>
                            <td>
                                @php
                                    $ks->jenis_transaksi;
                                    switch ($ks->jenis_transaksi) {
                                        case 'K':
                                            $bg = 'bg-danger';
                                            $lb = 'Keluar';
                                            break;
                                        case 'M':
                                            $bg = 'bg-success';
                                            $lb = 'Masuk';
                                            break;
                                    }
                                @endphp
                                <span class="badge {{ $bg }}">{{ $lb }}</span>
                            </td>
                            </td>
                            <td>{{ $ks->masuk }}</td>
                            <td>{{ $ks->keluar }}</td>
                            <td>{{ $ks->stock }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
