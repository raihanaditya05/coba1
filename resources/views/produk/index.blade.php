@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Manajemen Produk</h1>

    <!-- Notifikasi di sini -->
    @if(session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" id="error-alert">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tampilkan pesan kesalahan validasi -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tabel Daftar Produk -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Daftar Produk</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Deskripsi</th>
                                <th>Gambar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($produk->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada produk yang tersedia.</td>
                                </tr>
                            @else
                                @foreach($produk as $produkItem)
                                <tr>
                                    <td>{{ $produkItem->nama_produk }}</td>
                                    <td>{{ number_format($produkItem->harga_produk, 2, ',', '.') }}</td>
                                    <td>{{ $produkItem->deskripsi_produk }}</td>
                                    <td>
                                        @if($produkItem->gambar_produk)
                                            <img src="{{ asset('storage/' . $produkItem->gambar_produk) }}" alt="{{ $produkItem->nama_produk }}" style="width: 50px; height: auto;">
                                        @else
                                            Tidak ada gambar
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Tombol Edit -->
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditProduk{{ $produkItem->id }}">
                                            Edit
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalHapusProduk{{ $produkItem->id }}">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal Edit Produk -->
                                <div class="modal fade" id="modalEditProduk{{ $produkItem->id }}" tabindex="-1" aria-labelledby="modalEditProdukLabel{{ $produkItem->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalEditProdukLabel{{ $produkItem->id }}">Edit Produk</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('produk.update', $produkItem->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="form-group">
                                                        <label for="nama_produk">Nama Produk</label>
                                                        <input type="text" name="nama_produk" id="nama_produk" class="form-control" value="{{ old('nama_produk', $produkItem->nama_produk) }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="harga_produk">Harga</label>
                                                        <input type="number" name="harga_produk" id="harga_produk" class="form-control" value="{{ old('harga_produk', $produkItem->harga_produk) }}" min="0" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="deskripsi_produk">Deskripsi</label>
                                                        <textarea name="deskripsi_produk" id="deskripsi_produk" class="form-control">{{ old('deskripsi_produk', $produkItem->deskripsi_produk) }}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="gambar_produk">Gambar</label>
                                                        <input type="file" name="gambar_produk" id="gambar_produk" class="form-control">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary mt-3">Update Produk</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End of Modal Edit Produk -->

                                <!-- Modal Hapus Produk -->
                                <div class="modal fade" id="modalHapusProduk{{ $produkItem->id }}" tabindex="-1" aria-labelledby="modalHapusProdukLabel{{ $produkItem->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalHapusProdukLabel{{ $produkItem->id }}">Hapus Produk</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus produk <strong>{{ $produkItem->nama_produk }}</strong>?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <form action="{{ route('produk.destroy', $produkItem->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End of Modal Hapus Produk -->

                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Tambah Produk -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tambah Produk Baru</h5>

                    <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="nama_produk">Nama Produk</label>
                            <input type="text" name="nama_produk" id="nama_produk" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="harga_produk">Harga</label>
                            <input type="number" name="harga_produk" id="harga_produk" class="form-control" min="0" required>
                        </div>

                        <div class="form-group">
                            <label for="deskripsi_produk">Deskripsi</label>
                            <textarea name="deskripsi_produk" id="deskripsi_produk" class="form-control"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="gambar_produk">Gambar</label>
                            <input type="file" name="gambar_produk" id="gambar_produk" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Tambah Produk</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
