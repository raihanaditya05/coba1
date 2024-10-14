<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPenjualanExport;

class LaporanController extends Controller
{
    // Menampilkan halaman laporan dengan grafik dan detail penjualan
    public function index(Request $request)
    {
        // Mendapatkan periode dari request (harian, bulanan, tahunan)
        $periode = $request->get('periode', 'harian'); // Default 'harian'
        $tanggalSekarang = Carbon::now();
        $penjualans = [];

        // Filter transaksi berdasarkan periode yang dipilih
        if ($periode == 'harian') {
            $penjualans = Transaksi::whereDate('created_at', $tanggalSekarang->format('Y-m-d'))->get();
        } elseif ($periode == 'bulanan') {
            $penjualans = Transaksi::whereMonth('created_at', $tanggalSekarang->format('m'))
                                    ->whereYear('created_at', $tanggalSekarang->format('Y'))
                                    ->get();
        } elseif ($periode == 'tahunan') {
            $penjualans = Transaksi::whereYear('created_at', $tanggalSekarang->format('Y'))->get();
        }

        // Memproses data untuk grafik
        $label = [];
        $data = [];

        foreach ($penjualans as $penjualan) {
            $label[] = $penjualan->created_at->format('d-m-Y');
            $data[] = $penjualan->total_harga;
        }

        // Mengirim data ke tampilan laporan
        return view('laporan.index', [
            'penjualans' => $penjualans,
            'label' => $label,
            'data' => $data
        ]);
    }

    // Download laporan dalam format Excel
    public function download()
    {
        return Excel::download(new LaporanPenjualanExport, 'laporan_penjualan.xlsx');
    }
}
