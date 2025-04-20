<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        // Inisialisasi data default
        $data = [
            'totalProduk' => 0,
            'totalPelanggan' => 0,
            'totalPenjualan' => 0,
            'totalPengguna' => 0,
            'produkStokRendah' => [],
            'penjualanTerakhir' => [],
            'pelangganTerbaru' => [],
            'chartData' => [],
            'topProduk' => []
        ];

        // Cek keberadaan tabel dan ambil data
        if (Schema::hasTable('produk')) {
            $data['totalProduk'] = Produk::count();
            $data['produkStokRendah'] = Produk::where('Stok', '<', 10)
                                            ->orderBy('Stok', 'asc')
                                            ->take(5)
                                            ->get();
        }

        if (Schema::hasTable('pelanggan')) {
            $data['totalPelanggan'] = Pelanggan::count();
            $data['pelangganTerbaru'] = Pelanggan::latest()
                                            ->take(5)
                                            ->get();
        }

        if (Schema::hasTable('pengguna')) {
            $data['totalPengguna'] = Pengguna::count();
        }

        if (Schema::hasTable('penjualan')) {
            $data['totalPenjualan'] = Penjualan::count();
            $data['penjualanTerakhir'] = Penjualan::with(['pelanggan', 'pengguna'])
                                            ->latest()
                                            ->take(5)
                                            ->get();

            // Data untuk grafik penjualan per bulan
            $data['chartData'] = $this->getPenjualanPerbulan();
            
            // Top produk terlaris
            if (Schema::hasTable('detail_penjualan')) {
                $data['topProduk'] = $this->getTopProduk();
            }
        }

        return view('dashboard', $data);
    }

    private function getPenjualanPerbulan()
    {
        $tahunIni = date('Y');
        $result = [];

        try {
            $penjualanBulanan = DB::table('penjualan')
                ->select(DB::raw('MONTH(TanggalPenjualan) as bulan'), DB::raw('SUM(TotalHarga) as total'))
                ->whereYear('TanggalPenjualan', $tahunIni)
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get();

            $bulanIndonesia = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            // Isi data untuk semua bulan
            for ($i = 1; $i <= 12; $i++) {
                $found = false;
                foreach ($penjualanBulanan as $item) {
                    if ($item->bulan == $i) {
                        $result[] = [
                            'bulan' => $bulanIndonesia[$i - 1],
                            'total' => (float)$item->total
                        ];
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $result[] = [
                        'bulan' => $bulanIndonesia[$i - 1],
                        'total' => 0
                    ];
                }
            }
        } catch (\Exception $e) {
            // Jika ada error, return data kosong
            for ($i = 1; $i <= 12; $i++) {
                $result[] = [
                    'bulan' => $bulanIndonesia[$i - 1],
                    'total' => 0
                ];
            }
        }

        return $result;
    }

    private function getTopProduk()
    {
        try {
            return DB::table('detail_penjualan')
                ->join('produk', 'detail_penjualan.ProdukID', '=', 'produk.ProdukID')
                ->select(
                    'produk.ProdukID',
                    'produk.NamaProduk',
                    DB::raw('SUM(detail_penjualan.JumlahProduk) as total_terjual'),
                    DB::raw('SUM(detail_penjualan.Subtotal) as total_pendapatan')
                )
                ->groupBy('produk.ProdukID', 'produk.NamaProduk')
                ->orderBy('total_terjual', 'desc')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            return [];
        }
    }
}