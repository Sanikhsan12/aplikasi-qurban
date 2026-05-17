<?php

namespace App\Http\Controllers;

use App\Models\DanaDKM;
use Illuminate\Http\Request;

class DanaDKMController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $user = auth()->user();

        $dkm = DanaDKM::with(['order.user'])->paginate(10);

        $jumlahDana = $dkm->sum('jumlah_dana');

        return view('admin.keuangan.dkm.index', compact('dkm', 'jumlahDana', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();

        return view('admin.keuangan.dkm.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sumber_dana' => 'required|string|max:255',
            'jumlah_dana' => 'required|numeric|min:0',
            'keterangan'  => 'required|string',
        ]);

        DanaDkm::create([
            'sumber_dana' => $validated['sumber_dana'],
            'jumlah_dana' => $validated['jumlah_dana'],
            'keterangan'  => $validated['keterangan'],
        ]);

        return redirect()
            ->route('admin.dana-dkm.index')
            ->with('success', 'Data dana DKM berhasil disimpan.');
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
        //
    }
}
