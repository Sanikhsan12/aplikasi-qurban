<?php

namespace App\Http\Controllers;

use App\Models\BankPenerima;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $bank = BankPenerima::orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.keuangan.bank_penerima.index', compact('bank', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();

        return view('admin.keuangan.bank_penerima.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_bank' => 'required|string|max:100',
            'no_rek' => 'required|string|max:50|unique:bank_penerima,no_rek',
            'as_nama' => 'required|string|max:100',
        ], [
            'nama_bank.required' => 'Nama bank wajib diisi',
            'no_rek.required' => 'Nomor rekening wajib diisi',
            'no_rek.unique' => 'Nomor rekening sudah terdaftar',
            'as_nama.required' => 'Nama pemilik rekening wajib diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Terjadi kesalahan validasi');
        }

        try {
            BankPenerima::create([
                'nama_bank' => $request->nama_bank,
                'no_rek' => $request->no_rek,
                'as_nama' => $request->as_nama,
            ]);

            return redirect()->route('admin.bank-penerima.index')
                ->with('success', 'Data bank penerima berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $bank = BankPenerima::findOrFail($id);
            $bank->delete();

            return redirect()->route('admin.bank-penerima.index')
                ->with('success', 'Data bank penerima berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('bank-penerima.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
