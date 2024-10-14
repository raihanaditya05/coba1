@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Manajemen Stok Bahan</h1>

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

    <!-- Tabel Daftar Bahan dan Stok -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Daftar Bahan</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Bahan</th>
                                <th>Jumlah Stok</th>
                                <th>Satuan</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stok as $item)
                            <tr>
                                <td>{{ $item->masterBahan->nama_bahan }}</td>
                                <td>{{ $item->jumlah_stok }}</td>
                                <td>{{ $item->masterBahan->satuan }}</td>
                                <td>{{ $item->masterBahan->deskripsi_bahan }}</td>
                                <td>
                                    <!-- Tombol Edit -->
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditBahan{{ $item->masterBahan->id }}">
                                        Edit
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalHapusBahan{{ $item->masterBahan->id }}">
                                        Hapus
                                    </button>
                                </td>
                            </tr>


                            <!-- Modal Hapus Bahan -->
                            <div class="modal fade" id="modalHapusBahan{{ $item->masterBahan->id }}" tabindex="-1" aria-labelledby="modalHapusBahanLabel{{ $item->masterBahan->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalHapusBahanLabel{{ $item->masterBahan->id }}">Hapus Bahan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus bahan <strong>{{ $item->masterBahan->nama_bahan }}</strong>?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('masterbahan.destroy', $item->masterBahan->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End of Modal Hapus Bahan -->

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Tambah Stok -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tambah Stok Bahan</h5>

                    <form action="{{ route('stok.tambah') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="bahan_id">Pilih Bahan</label>
                            <select name="bahan_id" id="bahan_id" class="form-control" required>
                                <option value="">-- Pilih Bahan --</option>
                                @foreach($stok as $item)
                                    <option value="{{ $item->masterBahan->id }}">{{ $item->masterBahan->nama_bahan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="jumlah_stok">Jumlah Stok</label>
                            <input type="number" name="jumlah_stok" id="jumlah_stok" class="form-control" min="1" required>
                        </div>

                        <div class="form-group">
                            <label for="satuan">Satuan</label>
                            <select name="satuan" id="satuan" class="form-control" required>
                                <option value="kilogram">Kilogram</option>
                                <option value="gram">Gram</option>
                                <option value="liter">Liter</option>
                                <option value="mililiter">Mililiter</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Tambah Stok</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Opsi Tambah Bahan Baru -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tambah Bahan Baru</h5>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambahBahan">
                        Tambah Bahan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Bahan Baru -->
<div class="modal fade" id="modalTambahBahan" tabindex="-1" aria-labelledby="modalTambahBahanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahBahanLabel">Tambah Bahan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('masterbahan.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nama_bahan">Nama Bahan</label>
                        <input type="text" name="nama_bahan" id="nama_bahan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="satuan">Satuan</label>
                        <select name="satuan" id="satuan" class="form-control" required>
                            <option value="kilogram">Kilogram</option>
                            <option value="gram">Gram</option>
                            <option value="liter">Liter</option>
                            <option value="mililiter">Mililiter</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi_bahan">Deskripsi</label>
                        <textarea name="deskripsi_bahan" id="deskripsi_bahan" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="jumlah_stok">Jumlah Stok Awal</label>
                        <input type="number" name="jumlah_stok" id="jumlah_stok" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Simpan Bahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
