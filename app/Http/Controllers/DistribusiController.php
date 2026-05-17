<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Pelaksanaan;
use Illuminate\Http\Request;
use App\Models\Penyembelihan;
use App\Models\DistribusiDaging;
use App\Models\DistribusiDokumentasi;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Command\Command;

class DistribusiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $distribusi = DistribusiDaging::with([
            'pelaksanaan',
            'dokumentasi' // relasi hasMany
        ])
            ->latest()
            ->paginate(5);

        return view('admin.distribusi.index', compact('user', 'distribusi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();

        $usedPelaksanaanIds = DistribusiDaging::pluck('pelaksanaan_id');

        $pelaksanaans = Pelaksanaan::whereNotIn('id', $usedPelaksanaanIds)
            ->latest('id')
            ->take(1)
            ->get();

        return view('admin.distribusi.create', compact(
            'user',
            'pelaksanaans'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pelaksanaan_id' => 'required|exists:pelaksanaans,id',
            'dokumentasi' => 'required|array|min:1',
            'dokumentasi.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'link_gdrive' => 'nullable|url',
        ]);

        DB::transaction(function () use ($request) {

            // 1. Simpan data utama distribusi
            $distribusi = DistribusiDaging::create([
                'pelaksanaan_id' => $request->pelaksanaan_id,
                'link_gdrive' => $request->link_gdrive,
            ]);

            // 2. Simpan banyak file ke tabel distribusi_daging_fotos
            foreach ($request->file('dokumentasi') as $file) {
                $path = $file->store('distribusi_dokumentasi', 'public');

                $distribusi->dokumentasi()->create([
                    'file_path' => $path,
                ]);
            }
        });

        return redirect()
            ->back()
            ->with('success', 'Dokumentasi distribusi berhasil disimpan');
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

    // route tambahan    
    public function perkiraanPenerima()
    {
        $user = auth()->user();

        // Ambil pelaksanaan dengan status 'Active'
        $pelaksanaan = Pelaksanaan::where('status', 'Active')
            ->latest('id')
            ->first();

        // Jika tidak ada pelaksanaan Active, coba ambil yang terbaru
        if (!$pelaksanaan) {
            $pelaksanaan = Pelaksanaan::latest('id')->first();

            if (!$pelaksanaan) {
                return redirect()->back()->with('error', 'Tidak ada data pelaksanaan tersedia.');
            }
        }

        // Konfigurasi pembagian daging
        $kgPerOrang = 1; // UBAH JIKA PERLU

        // Validasi kgPerOrang
        if ($kgPerOrang <= 0) {
            return redirect()->back()->with('error', 'Konfigurasi pembagian daging tidak valid.');
        }

        // Ambil data order berdasarkan pelaksanaan yang aktif
        $dataTabel = Order::select(
            'jenis_hewan',
            DB::raw('SUM(perkiraan_daging) as total_daging')
        )
            ->where('pelaksanaan_id', $pelaksanaan->id)
            ->groupBy('jenis_hewan')
            ->orderBy('jenis_hewan')
            ->get()
            ->map(function ($item) use ($kgPerOrang) {
                $item->perkiraan_penerima = floor($item->total_daging / $kgPerOrang);
                return $item;
            });

        // Hitung total keseluruhan
        $totalDaging = $dataTabel->sum('total_daging');
        $totalPenerima = $dataTabel->sum('perkiraan_penerima');

        return view('admin.distribusi.perkiraan-penerima', compact(
            'user',
            'dataTabel',
            'kgPerOrang',
            'pelaksanaan',
            'totalDaging',
            'totalPenerima'
        ));
    }
}
