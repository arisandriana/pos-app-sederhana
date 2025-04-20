<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pelanggan = Pelanggan::all();
        return view('pelanggan.index', compact('pelanggan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pelanggan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'NamaPelanggan' => 'required|string|max:255',
            'Alamat' => 'nullable|string',
            'NomorTelepon' => 'nullable|string|max:15',
            'Email' => 'nullable|email|max:255',
            'Catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Pelanggan::create($request->all());

        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pelanggan $pelanggan)
    {
        return view('pelanggan.show', compact('pelanggan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validator = Validator::make($request->all(), [
            'NamaPelanggan' => 'required|string|max:255',
            'Alamat' => 'nullable|string',
            'NomorTelepon' => 'nullable|string|max:15',
            'Email' => 'nullable|email|max:255',
            'Catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $pelanggan->update($request->all());

        return redirect()->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pelanggan $pelanggan)
    {
        try {
            // Cek apakah pelanggan memiliki penjualan terkait
            if (Schema::hasTable('penjualan') && $pelanggan->penjualan()->exists()) {
                return redirect()->route('pelanggan.index')
                    ->with('error', 'Pelanggan tidak dapat dihapus karena memiliki transaksi terkait!');
            }

            $pelanggan->delete();

            return redirect()->route('pelanggan.index')
                ->with('success', 'Pelanggan berhasil dihapus!');
        } catch (\Exception $e) {
            // Tangani error jika tabel penjualan belum ada
            if (strpos($e->getMessage(), "Table 'pos_db.penjualans' doesn't exist") !== false || 
                strpos($e->getMessage(), "Table 'pos_db.penjualan' doesn't exist") !== false) {
                
                // Jika tabel penjualan belum ada, langsung hapus pelanggan
                $pelanggan->delete();
                
                return redirect()->route('pelanggan.index')
                    ->with('success', 'Pelanggan berhasil dihapus!');
            }
            
            // Untuk error lainnya, kembalikan pesan error
            return redirect()->route('pelanggan.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Pencarian pelanggan berdasarkan nama atau nomor telepon.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        if (empty($query)) {
            return redirect()->route('pelanggan.index');
        }
        
        $pelanggan = Pelanggan::search($query)->get();
        
        $searchActive = true;
        $searchQuery = $query;
        
        return view('pelanggan.index', compact('pelanggan', 'searchActive', 'searchQuery'));
    }

    /**
     * Tampilkan hasil pencarian pelanggan dalam format JSON (untuk AJAX)
     */
    public function searchJson(Request $request)
    {
        $query = $request->input('query');
        $pelanggan = [];
        
        if (!empty($query)) {
            $pelanggan = Pelanggan::search($query)
                ->select('PelangganID', 'NamaPelanggan', 'NomorTelepon', 'Alamat')
                ->take(10)
                ->get();
        }
        
        return response()->json($pelanggan);
    }
}