<?php

namespace App\Http\Controllers;

use App\Models\Sertifikat;
use App\Models\Penyembelihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PenyembelihanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        $penyembelihan = Penyembelihan::orderBy('created_at')
            ->paginate(10);

        return view('admin/penyembelihan.index', compact('penyembelihan', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        
        // Ambil data penyembelihan dengan status menunggu beserta nama user
        $penyembelihans = Penyembelihan::with(['order.user'])
            ->where('status', 'menunggu penyembelihan')
            ->get();

        return view('admin.penyembelihan/edit', compact('penyembelihans', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'penyembelihan_id' => 'required|exists:penyembelihans,id',
            'dokumentasi_penyembelihan' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $penyembelihan = Penyembelihan::findOrFail($request->penyembelihan_id);

            // Upload file
            if ($request->hasFile('dokumentasi_penyembelihan')) {
                $file = $request->file('dokumentasi_penyembelihan');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('penyembelihan-dokumentasi', $filename, 'public');

                // Update data
                $penyembelihan->update([
                    'status' => 'tersembelih',
                    'dokumentasi_penyembelihan' => $path
                ]);
            }

            $this->generateSertifikat($penyembelihan);

            // Perbaiki route redirect
            return redirect()->back()
                ->with('success', 'Status penyembelihan berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu penyembelihan,tersembelih,terdistribusi',
            'dokumentasi_penyembelihan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $penyembelihan = Penyembelihan::findOrFail($id);

            $wasTersembelih = $penyembelihan->isTersembelih();
            $newStatus = $request->status;

            $data = ['status' => $newStatus];

            // Upload file jika ada
            if ($request->hasFile('dokumentasi_penyembelihan')) {
                // Hapus file lama jika ada
                if ($penyembelihan->dokumentasi_penyembelihan) {
                    Storage::disk('public')->delete($penyembelihan->dokumentasi_penyembelihan);
                }

                $file = $request->file('dokumentasi_penyembelihan');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('penyembelihan-dokumentasi', $filename, 'public');
                $data['dokumentasi_penyembelihan'] = $path;
            }

            $penyembelihan->update($data);

            if (!$wasTersembelih && $newStatus === 'tersembelih') {
                $this->generateSertifikat($penyembelihan);
            }

            return redirect()->back()
                ->with('success', 'Data penyembelihan berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    protected function generateSertifikat(Penyembelihan $penyembelihan)
    {
        $order = $penyembelihan->order;
        $pesertaList = $order->peserta;

        $pelaksanaan = $penyembelihan->pelaksanaan;
        $tanggalSembelih = $pelaksanaan && $pelaksanaan->Penyembelihan
            ? \Carbon\Carbon::parse($pelaksanaan->Penyembelihan)
            : now();

        foreach ($pesertaList as $index => $peserta) {
            $nomorSertifikat = 'SERT/' . now()->format('Y/m/')
                . str_pad($order->id, 4, '0', STR_PAD_LEFT) . '/'
                . str_pad($index + 1, 2, '0', STR_PAD_LEFT);

            $sertifikat = Sertifikat::create([
                'order_id' => $order->id,
                'penyembelihan_id' => $penyembelihan->id,
                'order_peserta_id' => $peserta->id,
                'nomor_sertifikat' => $nomorSertifikat,
                'nama_peserta' => $peserta->nama_peserta,
                'jenis_hewan' => $order->jenis_hewan,
                'tanggal_penyembelihan' => $tanggalSembelih->toDateString(),
            ]);

            try {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.sertifikat', [
                    'sertifikat' => $sertifikat,
                    'order' => $order,
                    'pelaksanaan' => $pelaksanaan,
                ]);

                $filename = 'sertifikat_' . $sertifikat->id . '_' . time() . '.pdf';
                $path = 'sertifikat/' . $filename;
                $pdf->save(storage_path('app/public/' . $path));

                $sertifikat->update(['file_path' => $path]);
            } catch (\Throwable $e) {
                \Log::error('Generate Sertifikat Error', [
                    'sertifikat_id' => $sertifikat->id,
                    'message' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $penyembelihan = Penyembelihan::findOrFail($id);

            // Hapus file dokumentasi jika ada
            if ($penyembelihan->dokumentasi_penyembelihan) {
                Storage::disk('public')->delete($penyembelihan->dokumentasi_penyembelihan);
            }

            $penyembelihan->delete();

            // Perbaiki route redirect
            return redirect()->route('admin.penyembelihan.index')
                ->with('success', 'Data penyembelihan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // route baru
    public function waiting()
    {
        // Hanya tampilkan order dengan status 'menunggu penyembelihan'
        $penyembelihan = Penyembelihan::with(['order', 'pelaksanaan'])
            ->where('status', 'menunggu penyembelihan')
            ->latest()
            ->paginate(10);

        return view('admin/penyembelihan/menunggu', compact('penyembelihan'));
    }

    public function tersembelih()
    {
        // Hanya tampilkan order dengan status 'tersembelih'
        $penyembelihan = Penyembelihan::with(['order', 'pelaksanaan'])
            ->where('status', 'tersembelih')
            ->latest()
            ->paginate(10);

        return view('admin/penyembelihan/tersembelih', compact('penyembelihan'));
    }
}
