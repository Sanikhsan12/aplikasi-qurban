<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KetersediaanHewan;
use App\Http\Requests\StoreKetersediaanHewanRequest;
use App\Http\Requests\UpdateKetersediaanHewanRequest;
use App\Services\KetersediaanHewanService;
use Exception;

class KetersediaanHewanController extends Controller
{
    protected $hewanService;

    public function __construct(KetersediaanHewanService $hewanService)
    {
        $this->hewanService = $hewanService;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        $filters = $request->only(['jenis_hewan', 'min_bobot', 'max_bobot', 'min_harga', 'max_harga']);

        $ketersediaan = KetersediaanHewan::filter($filters)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        $jenisHewanOptions = KetersediaanHewan::distinct()->pluck('jenis_hewan');

        $statistics = [
            'total_hewan' => $ketersediaan->sum('jumlah'),
            'total_nilai' => $ketersediaan->sum('total_harga'),
            'rata_bobot' => $ketersediaan->avg('bobot'),
            'jenis_berbeda' => $jenisHewanOptions->count(),
        ];

        return view('admin/ketersediaan/index', compact('ketersediaan', 'jenisHewanOptions', 'statistics', 'filters', 'user'));
    }

    public function create()
    {
        $user = auth()->user();
        $jenisHewanOptions = ['Sapi', 'Kambing', 'Domba', 'Kerbau', 'Unta'];
        return view('admin/ketersediaan/create', compact('jenisHewanOptions', 'user'));
    }

    public function store(StoreKetersediaanHewanRequest $request)
    {
        try {
            $this->hewanService->createKetersediaan(
                $request->validated(), 
                $request->file('foto')
            );

            return redirect()->route('admin.ketersediaan-hewan.index')
                ->with('success', 'Data ketersediaan hewan berhasil ditambahkan.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data. Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = auth()->user();
        $hewan = KetersediaanHewan::findOrFail($id);
        return view('admin/ketersediaan/edit', compact('hewan', 'user'));
    }

    public function update(UpdateKetersediaanHewanRequest $request, KetersediaanHewan $ketersediaanHewan)
    {
        try {
            $this->hewanService->updateKetersediaan(
                $ketersediaanHewan,
                $request->validated(),
                $request->file('foto'),
                $request->boolean('remove_foto')
            );

            return redirect()->route('admin.ketersediaan-hewan.index')
                ->with('success', 'Data ketersediaan hewan berhasil diperbarui.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data. Error: ' . $e->getMessage());
        }
    }

    public function destroy(KetersediaanHewan $ketersediaanHewan)
    {
        try {
            $this->hewanService->deleteKetersediaan($ketersediaanHewan);
            
            return redirect()
                ->route('admin.ketersediaan-hewan.index')
                ->with('success', 'Data ketersediaan hewan berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()
                ->route('admin.ketersediaan-hewan.index')
                ->with('error', 'Gagal menghapus data.');
        }
    }
}
