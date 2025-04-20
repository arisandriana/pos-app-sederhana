<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman laporan utama
     */
    public function index()
    {
        return view('laporan.index');
    }

    /**
     * Laporan penjualan harian
     */
    public function penjualanHarian(Request $request)
    {
        $tanggal = $request->input('tanggal') ?? date('Y-m-d');

        $penjualan = Penjualan::with(['pelanggan', 'pengguna'])
            ->whereDate('TanggalPenjualan', $tanggal)
            ->get();

        $total = $penjualan->where('Status', 'selesai')->sum('TotalHarga');
        $jumlahTransaksi = $penjualan->where('Status', 'selesai')->count();
        
        return view('laporan.penjualan_harian', compact('penjualan', 'tanggal', 'total', 'jumlahTransaksi'));
    }

    /**
     * Laporan penjualan bulanan
     */
    public function penjualanBulanan(Request $request)
    {
        $bulan = $request->input('bulan') ?? date('m');
        $tahun = $request->input('tahun') ?? date('Y');
        
        $penjualan = Penjualan::with(['pelanggan', 'pengguna'])
            ->whereMonth('TanggalPenjualan', $bulan)
            ->whereYear('TanggalPenjualan', $tahun)
            ->get();
        
        // Kelompokkan penjualan berdasarkan tanggal
        $penjualanPerTanggal = $penjualan
            ->where('Status', 'selesai')
            ->groupBy(function($item) {
                return Carbon::parse($item->TanggalPenjualan)->format('Y-m-d');
            });

        // Hitung total dan jumlah transaksi per tanggal
        $dataPerTanggal = [];
        foreach ($penjualanPerTanggal as $tanggal => $items) {
            $dataPerTanggal[] = [
                'tanggal' => $tanggal,
                'total' => $items->sum('TotalHarga'),
                'jumlah_transaksi' => $items->count(),
            ];
        }

        $total = $penjualan->where('Status', 'selesai')->sum('TotalHarga');
        $jumlahTransaksi = $penjualan->where('Status', 'selesai')->count();
        
        return view('laporan.penjualan_bulanan', compact('dataPerTanggal', 'bulan', 'tahun', 'total', 'jumlahTransaksi'));
    }

    /**
     * Laporan penjualan per periode
     */
    public function penjualanPeriode(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai') ?? date('Y-m-d', strtotime('-30 days'));
        $tanggalSelesai = $request->input('tanggal_selesai') ?? date('Y-m-d');
        
        $penjualan = Penjualan::with(['pelanggan', 'pengguna'])
            ->whereBetween('TanggalPenjualan', [$tanggalMulai . ' 00:00:00', $tanggalSelesai . ' 23:59:59'])
            ->get();
        
        $total = $penjualan->where('Status', 'selesai')->sum('TotalHarga');
        $jumlahTransaksi = $penjualan->where('Status', 'selesai')->count();
        
        return view('laporan.penjualan_periode', compact('penjualan', 'tanggalMulai', 'tanggalSelesai', 'total', 'jumlahTransaksi'));
    }

    /**
     * Laporan produk terlaris
     */
    public function produkTerlaris(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai') ?? date('Y-m-d', strtotime('-30 days'));
        $tanggalSelesai = $request->input('tanggal_selesai') ?? date('Y-m-d');
        $limit = $request->input('limit') ?? 10;
        
        $produkTerlaris = DB::table('detail_penjualan')
            ->join('produk', 'detail_penjualan.ProdukID', '=', 'produk.ProdukID')
            ->join('penjualan', 'detail_penjualan.PenjualanID', '=', 'penjualan.PenjualanID')
            ->select(
                'produk.ProdukID',
                'produk.NamaProduk',
                'produk.Harga',
                DB::raw('SUM(detail_penjualan.JumlahProduk) as total_terjual'),
                DB::raw('SUM(detail_penjualan.Subtotal) as total_pendapatan')
            )
            ->whereBetween('penjualan.TanggalPenjualan', [$tanggalMulai . ' 00:00:00', $tanggalSelesai . ' 23:59:59'])
            ->where('penjualan.Status', 'selesai')
            ->groupBy('produk.ProdukID', 'produk.NamaProduk', 'produk.Harga')
            ->orderBy('total_terjual', 'desc')
            ->limit($limit)
            ->get();
        
        return view('laporan.produk_terlaris', compact('produkTerlaris', 'tanggalMulai', 'tanggalSelesai', 'limit'));
    }

    /**
     * Laporan stok produk
     */
    public function stokProduk()
    {
        $produk = Produk::orderBy('Stok', 'asc')->get();
        
        // Kelompokkan produk berdasarkan status stok
        $stokHabis = $produk->where('Stok', 0)->count();
        $stokRendah = $produk->where('Stok', '>', 0)->where('Stok', '<=', 10)->count();
        $stokAman = $produk->where('Stok', '>', 10)->count();
        
        return view('laporan.stok_produk', compact('produk', 'stokHabis', 'stokRendah', 'stokAman'));
    }

    /**
     * Laporan pelanggan terbaik
     */
    public function pelangganTerbaik(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai') ?? date('Y-m-d', strtotime('-30 days'));
        $tanggalSelesai = $request->input('tanggal_selesai') ?? date('Y-m-d');
        $limit = $request->input('limit') ?? 10;
        
        $pelangganTerbaik = DB::table('penjualan')
            ->join('pelanggan', 'penjualan.PelangganID', '=', 'pelanggan.PelangganID')
            ->select(
                'pelanggan.PelangganID',
                'pelanggan.NamaPelanggan',
                'pelanggan.NomorTelepon',
                DB::raw('COUNT(penjualan.PenjualanID) as total_transaksi'),
                DB::raw('SUM(penjualan.TotalHarga) as total_belanja')
            )
            ->whereBetween('penjualan.TanggalPenjualan', [$tanggalMulai . ' 00:00:00', $tanggalSelesai . ' 23:59:59'])
            ->where('penjualan.Status', 'selesai')
            ->groupBy('pelanggan.PelangganID', 'pelanggan.NamaPelanggan', 'pelanggan.NomorTelepon')
            ->orderBy('total_belanja', 'desc')
            ->limit($limit)
            ->get();
        
        return view('laporan.pelanggan_terbaik', compact('pelangganTerbaik', 'tanggalMulai', 'tanggalSelesai', 'limit'));
    }

    /**
     * Laporan pendapatan per kasir
     */
    public function pendapatanPerKasir(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai') ?? date('Y-m-d', strtotime('-30 days'));
        $tanggalSelesai = $request->input('tanggal_selesai') ?? date('Y-m-d');
        
        $pendapatanPerKasir = DB::table('penjualan')
            ->join('pengguna', 'penjualan.PenggunaID', '=', 'pengguna.PenggunaID')
            ->select(
                'pengguna.PenggunaID',
                'pengguna.NamaPengguna',
                DB::raw('COUNT(penjualan.PenjualanID) as total_transaksi'),
                DB::raw('SUM(penjualan.TotalHarga) as total_pendapatan')
            )
            ->whereBetween('penjualan.TanggalPenjualan', [$tanggalMulai . ' 00:00:00', $tanggalSelesai . ' 23:59:59'])
            ->where('penjualan.Status', 'selesai')
            ->groupBy('pengguna.PenggunaID', 'pengguna.NamaPengguna')
            ->orderBy('total_pendapatan', 'desc')
            ->get();
        
        return view('laporan.pendapatan_per_kasir', compact('pendapatanPerKasir', 'tanggalMulai', 'tanggalSelesai'));
    }

    /**
     * Export laporan ke PDF
     */
    public function exportPDF(Request $request)
    {
        // Logika untuk mengexport laporan ke PDF
        // Implementasi akan menggunakan library seperti DomPDF atau Snappy PDF
    }

    /**
     * Export laporan ke Excel
     */
    public function exportExcel(Request $request)
    {
        // Logika untuk mengexport laporan ke Excel
        // Implementasi akan menggunakan library seperti Laravel Excel
    }
}