<?php

namespace App\Http\Controllers;

use App\Models\DanaDKM;
use Illuminate\Http\Request;
use App\Models\DanaOperasional;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DanaOperasionalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $dana_operasional = DanaOperasional::with(['user', 'danaDkm'])->paginate(10);

        $jumlahDana = $dana_operasional->sum('jumlah_pengeluaran');

        return view('admin.keuangan.operasional.index', compact('dana_operasional', 'jumlahDana', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        
        $danaDkm = DanaDkm::where('jumlah_dana', '>', 0)
            ->orderBy('sumber_dana')
            ->get();

        return view('admin.keuangan.operasional.create', compact('danaDkm', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_dkm' => 'required|exists:dana_dkm,id',
            'keperluan' => 'required|string|max:255',
            'jumlah_pengeluaran' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $danaDkm = DanaDKM::lockForUpdate()->findOrFail($request->id_dkm);

            // CEK SALDO
            if ($danaDkm->jumlah_dana < $request->jumlah_pengeluaran) {
                return back()
                    ->withErrors(['jumlah_pengeluaran' => 'Saldo dana tidak mencukupi'])
                    ->withInput();
            }

            // SIMPAN PENGELUARAN
            DanaOperasional::create([
                'id_dkm' => $danaDkm->id,
                'id_user' => Auth::id(),
                'keperluan' => $request->keperluan,
                'jumlah_pengeluaran' => $request->jumlah_pengeluaran,
                'keterangan' => $request->keterangan,
            ]);

            // KURANGI SALDO
            $danaDkm->decrement('jumlah_dana', $request->jumlah_pengeluaran);

            DB::commit();

            return redirect()
                ->route('admin.dana-operasional.index')
                ->with('success', 'Pengeluaran berhasil dicatat dan saldo diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data.')
                ->withInput();
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
        //
    }
}
