@extends('layouts.app')

@section('title', 'Detail Penjualan')

@section('content')
    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <h2 class="mb-0">Detail Penjualan</h2>
                <p class="text-muted small mb-0">Manage detail of Penjualan.</p>
            </div>
        </div>
    </div>

    {{-- back to penjualan --}}
    <div class="mb-3">
        <a href="{{ url('/manage_penjualan') }}" class="btn btn-secondary btn-sm">Back to Penjualan</a>
    </div>

    {{-- Penjualan summary (if available) --}}
    @if (isset($penjualan))
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3"><strong>No. Nota:</strong>
                        {{ $penjualan->no_nota ?? ($penjualan->idpenjualan ?? '-') }}
                    </div>
                    <div class="col-md-3"><strong>Tanggal:</strong>
                        {{ isset($penjualan->created_at) ? \Carbon\Carbon::parse($penjualan->created_at)->format('d M Y H:i') : $penjualan->tanggal ?? '-' }}
                    </div>
                    <div class="col-md-3 text-end">
                        <strong>Total Nilai:</strong>
                        {{ isset($penjualan->total_nilai) ? number_format($penjualan->total_nilai, 0, ',', '.') : '-' }}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Detail Penjualan Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th style="width:60px">No</th>
                            <th style="width:120px">ID Detail</th>
                            <th>Barang</th>
                            <th style="width:140px" class="text-end">Harga Satuan</th>
                            <th style="width:100px" class="text-center">Jumlah</th>
                            <th style="width:140px" class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($detailPenjualans ?? [] as $detail)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $detail->iddetail_penjualan ?? '-' }}</td>
                                <td>{{ $detail->nama_barang ?? '-' }}</td>
                                <td class="text-end">
                                    @if (isset($detail->harga_satuan))
                                        {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                    @elseif(isset($detail->harga))
                                        {{ number_format($detail->harga, 0, ',', '.') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ $detail->jumlah ?? ($detail->qty ?? ($detail->kuantitas ?? '-')) }}
                                </td>
                                <td class="text-end">
                                    @php
                                        $subtotal =
                                            $detail->subtotal ?? ($detail->sub_total ?? null);
                                        if (!$subtotal) {
                                            if (isset($detail->harga_satuan, $detail->jumlah)) {
                                                $subtotal = $detail->harga_satuan * $detail->jumlah;
                                            } elseif (isset($detail->harga, $detail->qty)) {
                                                $subtotal = $detail->harga * $detail->qty;
                                            }
                                        }
                                    @endphp
                                    {{ isset($subtotal) ? number_format($subtotal, 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No detail penjualan found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
