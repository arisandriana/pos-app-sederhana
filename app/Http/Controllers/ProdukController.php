<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produk = Produk::all();
        return view('produk.index', compact('produk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('produk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'NamaProduk' => 'required|string|max:255',
            'Harga' => 'required|numeric|min:0',
            'Stok' => 'required|integer|min:0',
            'Deskripsi' => 'nullable|string',
            'Barcode' => 'nullable|string|max:255|unique:produk',
            'Gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        $data = $request->all();
    
        // Upload gambar jika ada
        if ($request->hasFile('Gambar')) {
            $gambar = $request->file('Gambar');
            $namaFile = time() . '.' . $gambar->getClientOriginalExtension();
            
            // Simpan gambar menggunakan path absolut
            $gambar->move(public_path('storage/produk'), $namaFile);
            
            // Simpan hanya nama file di database
            $data['Gambar'] = $namaFile;
            
            // Debug info
            info('Gambar berhasil disimpan: ' . public_path('storage/produk/' . $namaFile));
        }
    
        Produk::create($data);
    
        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        return view('produk.show', compact('produk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $produk)
    {
        return view('produk.edit', compact('produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $validator = Validator::make($request->all(), [
            'NamaProduk' => 'required|string|max:255',
            'Harga' => 'required|numeric|min:0',
            'Stok' => 'required|integer|min:0',
            'Deskripsi' => 'nullable|string',
            'Barcode' => 'nullable|string|max:255|unique:produk,Barcode,' . $produk->ProdukID . ',ProdukID',
            'Gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
    
        $data = $request->all();
    
        // Upload gambar jika ada
        if ($request->hasFile('Gambar')) {
            // Hapus gambar lama jika ada
            if ($produk->Gambar && file_exists(public_path('storage/produk/' . $produk->Gambar))) {
                unlink(public_path('storage/produk/' . $produk->Gambar));
            }
    
            $gambar = $request->file('Gambar');
            $namaFile = time() . '.' . $gambar->getClientOriginalExtension();
            
            // Simpan gambar menggunakan path absolut
            $gambar->move(public_path('storage/produk'), $namaFile);
            
            // Simpan hanya nama file di database
            $data['Gambar'] = $namaFile;
            
            // Debug info
            info('Gambar berhasil diupdate: ' . public_path('storage/produk/' . $namaFile));
        }
    
        $produk->update($data);
    
        return redirect()->route('produk.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        try {
            // Hapus gambar jika ada
            if ($produk->Gambar && file_exists(public_path('storage/produk/' . $produk->Gambar))) {
                unlink(public_path('storage/produk/' . $produk->Gambar));
                info('Gambar berhasil dihapus: ' . public_path('storage/produk/' . $produk->Gambar));
            }
    
            // Hapus produk
            $produk->delete();
    
            return redirect()->route('produk.index')
                ->with('success', 'Produk berhasil dihapus!');
        } catch (\Exception $e) {
            // Untuk error, kembalikan pesan error
            return redirect()->route('produk.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Pencarian produk berdasarkan nama atau barcode.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        if (empty($query)) {
            return redirect()->route('produk.index');
        }
        
        $produk = Produk::where('NamaProduk', 'like', "%{$query}%")
            ->orWhere('Barcode', 'like', "%{$query}%")
            ->get();
        
        $searchActive = true;
        $searchQuery = $query;
        
        return view('produk.index', compact('produk', 'searchActive', 'searchQuery'));
    }

    /**
     * Filter produk berdasarkan stok.
     */
    public function filterByStock($filter)
    {
        if ($filter === 'low') {
            $produk = Produk::where('Stok', '<', 10)->get();
            $filterText = 'Stok Rendah (< 10)';
        } elseif ($filter === 'out') {
            $produk = Produk::where('Stok', '=', 0)->get();
            $filterText = 'Stok Habis';
        } else {
            return redirect()->route('produk.index');
        }

        $filterActive = true;
        
        return view('produk.index', compact('produk', 'filterActive', 'filterText'));
    }

    /**
     * Tambah stok produk.
     */
    public function addStock(Request $request, Produk $produk)
    {
        $validator = Validator::make($request->all(), [
            'jumlah' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $produk->tambahStok($request->jumlah);

        return redirect()->route('produk.show', $produk->ProdukID)
            ->with('success', 'Stok berhasil ditambahkan!');
    }
}