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
