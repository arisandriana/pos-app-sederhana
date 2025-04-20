<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penggunas = Pengguna::all();
        return view('pengguna.index', compact('penggunas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pengguna.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'NamaPengguna' => 'required|string|max:255',
            'Email' => 'required|string|email|max:255|unique:pengguna',
            'KataSandi' => 'required|string|min:8',
            'KonfirmasiKataSandi' => 'required|same:KataSandi',
            'Peran' => 'required|in:Admin,Kasir,Manager',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Pengguna::create([
            'NamaPengguna' => $request->NamaPengguna,
            'Email' => $request->Email,
            'KataSandi' => Hash::make($request->KataSandi),
            'Peran' => $request->Peran,
        ]);

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengguna $pengguna)
    {
        return view('pengguna.show', compact('pengguna'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengguna $pengguna)
    {
        return view('pengguna.edit', compact('pengguna'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengguna $pengguna)
    {
        $validator = Validator::make($request->all(), [
            'NamaPengguna' => 'required|string|max:255',
            'Email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('pengguna')->ignore($pengguna->PenggunaID, 'PenggunaID'),
            ],
            'Peran' => 'required|in:Admin,Kasir,Manager',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'NamaPengguna' => $request->NamaPengguna,
            'Email' => $request->Email,
            'Peran' => $request->Peran,
        ];

        // Update password only if provided
        if ($request->filled('KataSandi')) {
            $validator = Validator::make($request->all(), [
                'KataSandi' => 'required|string|min:8',
                'KonfirmasiKataSandi' => 'required|same:KataSandi',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data['KataSandi'] = Hash::make($request->KataSandi);
        }

        $pengguna->update($data);

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengguna $pengguna)
    {
        try {
            // Cek apakah pengguna memiliki penjualan terkait
            if (Schema::hasTable('penjualan') && $pengguna->penjualan()->exists()) {
                return redirect()->route('pengguna.index')
                    ->with('error', 'Pengguna tidak dapat dihapus karena memiliki data penjualan terkait!');
            }

            $pengguna->delete();

            return redirect()->route('pengguna.index')
                ->with('success', 'Pengguna berhasil dihapus!');
        } catch (\Exception $e) {
            // Tangani error jika tabel penjualan belum ada
            if (strpos($e->getMessage(), "Table 'pos_db.penjualans' doesn't exist") !== false ||
                strpos($e->getMessage(), "Table 'pos_db.penjualan' doesn't exist") !== false) {
                
                // Jika tabel penjualan belum ada, langsung hapus pengguna
                $pengguna->delete();
                
                return redirect()->route('pengguna.index')
                    ->with('success', 'Pengguna berhasil dihapus!');
            }
            
            // Untuk error lainnya, kembalikan pesan error
            return redirect()->route('pengguna.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}