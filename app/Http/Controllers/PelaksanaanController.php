<?php

namespace App\Http\Controllers;

use App\Models\Pelaksanaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PelaksanaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $user = auth()->user();

        $pelaksanaan = Pelaksanaan::orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin/pelaksanaan.index', compact('pelaksanaan', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();

        return view('admin/pelaksanaan.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Tanggal_Pendaftaran' => 'required|date',
            'Tanggal_Penutupan' => 'required|date|after_or_equal:Tanggal_Pendaftaran',
            'Ketuplak' => 'required|string|max:100',
            'Lokasi' => 'required|string|max:200',
            'Penyembelihan' => 'required|date|after_or_equal:Tanggal_Penutupan',
        ], [
            'Tanggal_Pendaftaran.required' => 'Tanggal pendaftaran harus diisi.',
            'Tanggal_Pendaftaran.date' => 'Format tanggal pendaftaran tidak valid.',
            'Tanggal_Penutupan.required' => 'Tanggal penutupan harus diisi.',
            'Tanggal_Penutupan.date' => 'Format tanggal penutupan tidak valid.',
            'Tanggal_Penutupan.after_or_equal' => 'Tanggal penutupan harus sama atau setelah tanggal pendaftaran.',
            'Ketuplak.required' => 'Nama ketuplak harus diisi.',
            'Ketuplak.max' => 'Nama ketuplak maksimal 100 karakter.',
            'Lokasi.required' => 'Lokasi harus diisi.',
            'Lokasi.max' => 'Lokasi maksimal 200 karakter.',
            'Penyembelihan.required' => 'Tanggal penyembelihan harus diisi.',
            'Penyembelihan.date' => 'Format tanggal penyembelihan tidak valid.',
            'Penyembelihan.after_or_equal' => 'Tanggal penyembelihan harus sama atau setelah tanggal penutupan.',
        ]);

        try {
            DB::beginTransaction();

            Pelaksanaan::create([
                'Tanggal_Pendaftaran' => $validated['Tanggal_Pendaftaran'],
                'Tanggal_Penutupan' => $validated['Tanggal_Penutupan'],
                'Ketuplak' => $validated['Ketuplak'],
                'Lokasi' => $validated['Lokasi'],
                'Penyembelihan' => $validated['Penyembelihan'],
            ]);

            DB::commit();

            return redirect()->route('admin.pelaksanaan.index')
                ->with('success', 'Data pelaksanaan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data pelaksanaan. Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pelaksanaan $pelaksanaan)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        
        $user = auth()->user();

        $pelaksanaan = Pelaksanaan::findOrFail($id);

        return view('admin.pelaksanaan.edit', compact('pelaksanaan', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pelaksanaan $pelaksanaan)
    {
        $validated = $request->validate([
            'Tanggal_Pendaftaran' => 'required|date',
            'Tanggal_Penutupan' => 'required|date|after_or_equal:Tanggal_Pendaftaran',
            'Ketuplak' => 'required|string|max:100',
            'Lokasi' => 'required|string|max:200',
            'Penyembelihan' => 'required|date|after_or_equal:Tanggal_Penutupan',
            'Status' => 'required',
        ], [
            'Tanggal_Penutupan.after_or_equal' => 'Tanggal penutupan harus sama atau setelah tanggal pendaftaran.',
            'Penyembelihan.after_or_equal' => 'Tanggal penyembelihan harus sama atau setelah tanggal penutupan.',
        ]);

        try {
            DB::beginTransaction();

            $pelaksanaan->update($validated);

            DB::commit();

            return redirect()->route('admin.pelaksanaan.index')
                ->with('success', 'Data pelaksanaan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data pelaksanaan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pelaksanaan $pelaksanaan)
    {
        try {
            $pelaksanaan->delete();

            return redirect()->route('admin.pelaksanaan.index')
                ->with('success', 'Data pelaksanaan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.pelaksanaan.index')
                ->with('error', 'Gagal menghapus data pelaksanaan.');
        }
    }
}
