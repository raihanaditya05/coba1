<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\MasterBahan;
use App\Models\ProdukBahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        // Menampilkan halaman daftar produk
        $produk = Produk::with('produkBahan.masterBahan')->get(); // Menyertakan relasi
        return view('produk.index', compact('produk')); // Mengirim variabel produk ke view
    }

    public function create()
    {
        // Menampilkan form untuk menambahkan produk baru
        $bahan = MasterBahan::all(); // Ambil semua data bahan
        return view('produk.create', compact('bahan')); // Pastikan $bahan dikirim ke view
    }

    public function store(Request $request)
    {
        Log::info($request->all());

        // Validasi input
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_produk' => 'required|numeric',
            'deskripsi_produk' => 'nullable|string',
            'gambar_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bahan' => 'required|array',
            'bahan.*' => 'exists:master_bahan,id',
            'jumlah_stok' => 'required|array',
            'jumlah_stok.*' => 'numeric|min:0',
        ]);

        // Upload gambar produk jika ada
        $gambarPath = $request->hasFile('gambar_produk') 
            ? $request->file('gambar_produk')->store('produk', 'public') 
            : null;

        // Buat produk baru
        $produk = Produk::create([
            'nama_produk' => $request->nama_produk,
            'harga_produk' => $request->harga_produk,
            'deskripsi_produk' => $request->deskripsi_produk,
            'gambar_produk' => $gambarPath,
        ]);

        // Simpan bahan-bahan yang diperlukan untuk produk
        foreach ($request->bahan as $key => $idbahan) {
            ProdukBahan::create([
                'idproduk' => $produk->id,
                'idbahan' => $idbahan,
                'jumlah_bahan' => $request->jumlah_stok[$key],
            ]);
        }

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // Menampilkan form untuk mengedit produk
        $produk = Produk::with('produkBahan.masterBahan')->findOrFail($id);
        $bahan = MasterBahan::all(); // Ambil semua bahan
        return view('produk.edit', compact('produk', 'bahan')); // Pastikan $bahan dikirim ke view
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_produk' => 'required|numeric',
            'deskripsi_produk' => 'nullable|string',
            'gambar_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bahan' => 'required|array',
            'bahan.*' => 'exists:master_bahan,id',
            'jumlah_stok' => 'required|array',
            'jumlah_stok.*' => 'numeric|min:0',
        ]);

        // Temukan produk yang akan diupdate
        $produk = Produk::findOrFail($id);

        // Periksa dan simpan gambar baru jika diupload
        if ($request->hasFile('gambar_produk')) {
            // Hapus gambar lama jika ada
            if ($produk->gambar_produk) {
                Storage::disk('public')->delete($produk->gambar_produk);
            }

            // Simpan gambar baru
            $gambarPath = $request->file('gambar_produk')->store('produk', 'public');
            $produk->gambar_produk = $gambarPath;
        }

        // Update data produk
        $produk->update([
            'nama_produk' => $request->nama_produk,
            'harga_produk' => $request->harga_produk,
            'deskripsi_produk' => $request->deskripsi_produk,
        ]);

        // Hapus bahan-bahan lama terkait produk
        ProdukBahan::where('idproduk', $produk->id)->delete();

        // Simpan bahan-bahan baru
        foreach ($request->bahan as $key => $idbahan) {
            ProdukBahan::create([
                'idproduk' => $produk->id,
                'idbahan' => $idbahan,
                'jumlah_bahan' => $request->jumlah_stok[$key],
            ]);
        }

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Hapus produk beserta bahan-bahannya
        $produk = Produk::findOrFail($id);

        // Hapus gambar produk jika ada
        if ($produk->gambar_produk) {
            Storage::disk('public')->delete($produk->gambar_produk);
        }

        // Hapus bahan-bahan produk
        ProdukBahan::where('idproduk', $produk->id)->delete();

        // Hapus produk
        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus!');
    }
}
