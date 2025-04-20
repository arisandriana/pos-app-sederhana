<?php

namespace App\Http\Controllers;

use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penjualan = Penjualan::with(['pelanggan', 'pengguna'])
            ->orderBy('TanggalPenjualan', 'desc')
            ->paginate(10);
        
        return view('penjualan.index', compact('penjualan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua produk dengan stok > 0
        $produk = Produk::where('Stok', '>', 0)->get();
        $pelanggan = Pelanggan::orderBy('NamaPelanggan')->get();
        
        return view('penjualan.create', compact('produk', 'pelanggan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi request
        $validator = Validator::make($request->all(), [
            'produk_id' => 'required|array',
            'produk_id.*' => 'required|exists:produk,ProdukID',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
            'harga' => 'required|array',
            'harga.*' => 'required|numeric|min:0',
            'subtotal' => 'required|array',
            'subtotal.*' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'bayar' => 'required|numeric|min:0',
            'kembali' => 'required|numeric',
            'pelanggan_id' => 'nullable|exists:pelanggan,PelangganID',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validasi jumlah bayar
        if ($request->bayar < $request->total) {
            return redirect()->back()
                ->with('error', 'Jumlah pembayaran kurang dari total belanja!')
                ->withInput();
        }

        // Validasi stok produk
        $isStokTersedia = true;
        $produkKurang = [];

        foreach ($request->produk_id as $key => $id) {
            $produk = Produk::find($id);
            if (!$produk || $produk->Stok < $request->jumlah[$key]) {
                $isStokTersedia = false;
                $produkKurang[] = $produk ? $produk->NamaProduk : 'Produk ID: ' . $id;
            }
        }

        if (!$isStokTersedia) {
            return redirect()->back()
                ->with('error', 'Stok tidak mencukupi untuk produk: ' . implode(', ', $produkKurang))
                ->withInput();
        }

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Buat penjualan baru
            $penjualan = Penjualan::create([
                'TanggalPenjualan' => now(),
                'TotalHarga' => $request->total,
                'Bayar' => $request->bayar,
                'Kembali' => $request->kembali,
                'PelangganID' => $request->pelanggan_id,
                'PenggunaID' => Auth::id(),
                'Status' => 'selesai',
                'Catatan' => $request->catatan,
            ]);

            // Simpan detail penjualan
            foreach ($request->produk_id as $key => $id) {
                $produk = Produk::find($id);
                
                // Buat detail penjualan
                DetailPenjualan::create([
                    'PenjualanID' => $penjualan->PenjualanID,
                    'ProdukID' => $id,
                    'JumlahProduk' => $request->jumlah[$key],
                    'HargaSatuan' => $request->harga[$key],
                    'Subtotal' => $request->subtotal[$key],
                ]);

                // Kurangi stok produk
                $produk->kurangiStok($request->jumlah[$key]);
            }

            DB::commit();

            return redirect()->route('penjualan.show', $penjualan->PenjualanID)
                ->with('success', 'Transaksi berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Penjualan $penjualan)
    {
        $penjualan->load(['pelanggan', 'pengguna', 'detailPenjualan.produk']);
        return view('penjualan.show', compact('penjualan'));
    }

    /**
     * Cetak nota penjualan.
     */
    public function printInvoice(Penjualan $penjualan)
    {
        $penjualan->load(['pelanggan', 'pengguna', 'detailPenjualan.produk']);
        return view('penjualan.invoice', compact('penjualan'));
    }

    /**
     * Batalkan penjualan dan kembalikan stok.
     */
    public function cancel(Penjualan $penjualan)
    {
        if ($penjualan->Status === 'batal') {
            return redirect()->back()->with('error', 'Penjualan ini sudah dibatalkan sebelumnya.');
        }

        DB::beginTransaction();

        try {
            // Kembalikan stok produk
            foreach ($penjualan->detailPenjualan as $detail) {
                $produk = $detail->produk;
                if ($produk) {
                    $produk->tambahStok($detail->JumlahProduk);
                }
            }

            // Update status penjualan
            $penjualan->update(['Status' => 'batal']);

            DB::commit();
            return redirect()->route('penjualan.show', $penjualan->PenjualanID)
                ->with('success', 'Penjualan berhasil dibatalkan dan stok produk dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Cari penjualan berdasarkan tanggal.
     */
    public function search(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $status = $request->status;
        $invoice = $request->invoice;
        
        $query = Penjualan::with(['pelanggan', 'pengguna']);
        
        if ($start_date && $end_date) {
            $query->whereBetween('TanggalPenjualan', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
        }
        
        if ($status) {
            $query->where('Status', $status);
        }
        
        if ($invoice) {
            $penjualanID = str_replace('INV-', '', $invoice);
            $penjualanID = ltrim($penjualanID, '0');
            $query->where('PenjualanID', $penjualanID);
        }
        
        $penjualan = $query->orderBy('TanggalPenjualan', 'desc')->paginate(10);
        
        return view('penjualan.index', compact('penjualan', 'start_date', 'end_date', 'status', 'invoice'));
    }
}