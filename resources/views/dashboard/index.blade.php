@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="my-4">Dashboard</h1>
        </div>
    </div>
    
    <!-- Informasi Ringkasan -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Produk</h5>
                    <p class="card-text">{{ $totalProduk }} Produk</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Bahan</h5>
                    <p class="card-text">{{ $totalBahan }} Bahan</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Transaksi Hari Ini</h5>
                    <p class="card-text">{{ $transaksiHariIni }} Transaksi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Produk Terlaris -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Produk Terlaris</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Jumlah Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produkTerlaris as $produk)
                            <tr>
                                <td>{{ $produk->nama_produk }}</td>
                                <td>{{ $produk->detailtransaksi_count }} kali terjual</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Stok Menipis -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Stok Bahan Menipis</h5>
                    @if($stokMenipis->isEmpty())
                        <p class="text-success">Semua bahan cukup.</p>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama Bahan</th>
                                    <th>Jumlah Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stokMenipis as $bahan)
                                <tr>
                                    <td>{{ $bahan->nama_bahan }}</td>
                                    <td>{{ $bahan->jumlah_stok }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
