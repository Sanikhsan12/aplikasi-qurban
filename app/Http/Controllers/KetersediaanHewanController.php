<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Http\Request;
use App\Models\KetersediaanHewan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KetersediaanHewanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        
        $jenisHewanOptions = ['Sapi', 'Kambing', 'Domba', 'Kerbau', 'Unta'];
        return view('admin/ketersediaan/create', compact('jenisHewanOptions', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_hewan' => 'required|string|max:50',
            'bobot' => 'required|numeric|min:0|max:1000',
            'harga' => 'required|numeric|min:0',
            'jumlah' => 'required|integer|min:1',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'jenis_hewan.required' => 'Jenis hewan harus diisi.',
            'bobot.required' => 'Bobot hewan harus diisi.',
            'bobot.min' => 'Bobot tidak boleh kurang dari 0 kg.',
            'bobot.max' => 'Bobot maksimal 1000 kg.',
            'harga.required' => 'Harga hewan harus diisi.',
            'harga.min' => 'Harga tidak boleh kurang dari 0.',
            'jumlah.required' => 'Jumlah hewan harus diisi.',
            'jumlah.min' => 'Jumlah minimal 1 ekor.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar yang diperbolehkan: jpeg, png, jpg, gif.',
            'foto.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            DB::beginTransaction();

            // Handle file upload
            if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
                $file = $request->file('foto');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // 1. Buat folder jika belum ada
                $folderPath = storage_path('app/public/hewan');
                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0755, true);
                    \Log::info('Created directory:', [$folderPath]);
                }

                // 2. Pindahkan file secara manual
                $destinationPath = $folderPath . '/' . $filename;

                // Coba pindahkan
                if ($file->move($folderPath, $filename)) {
                    \Log::info('File moved successfully to:', [$destinationPath]);
                    $validated['foto'] = $filename;
                } else {
                    \Log::error('Failed to move file');
                    $validated['foto'] = null;
                }

                // 3. Verifikasi
                if (file_exists($destinationPath)) {
                    \Log::info('✅ File verified at:', [$destinationPath]);
                    \Log::info('File size:', [filesize($destinationPath)]);
                }
            }

            KetersediaanHewan::create($validated);

            DB::commit();

            return redirect()->route('admin.ketersediaan-hewan.index')
                ->with('success', 'Data ketersediaan hewan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded file if error
            if (isset($filename) && Storage::exists('public/hewan/' . $filename)) {
                Storage::delete('public/hewan/' . $filename);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan data. Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(KetersediaanHewan $ketersediaanHewan)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = auth()->user();
        
        $hewan = KetersediaanHewan::findOrFail($id);
        return view('admin/ketersediaan/edit', compact('hewan', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KetersediaanHewan $ketersediaanHewan)
    {
        $validated = $request->validate([
            'jenis_hewan' => 'required|string|max:50',
            'bobot' => 'required|numeric|min:0|max:1000',
            'harga' => 'required|numeric|min:0',
            'jumlah' => 'required|integer|min:1',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'jenis_hewan.required' => 'Jenis hewan harus diisi.',
            'bobot.required' => 'Bobot hewan harus diisi.',
            'bobot.min' => 'Bobot tidak boleh kurang dari 0 kg.',
            'bobot.max' => 'Bobot maksimal 1000 kg.',
            'harga.required' => 'Harga hewan harus diisi.',
            'harga.min' => 'Harga tidak boleh kurang dari 0.',
            'jumlah.required' => 'Jumlah hewan harus diisi.',
            'jumlah.min' => 'Jumlah minimal 1 ekor.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format gambar yang diperbolehkan: jpeg, png, jpg, gif.',
            'foto.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            DB::beginTransaction();

            // Simpan nama file lama SEBELUM proses upload
            $oldPhoto = $ketersediaanHewan->foto;

            // Handle file upload - PERSIS SEPERTI STORE
            if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
                $file = $request->file('foto');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                \Log::info('🔄 UPDATE - Starting file upload:', [
                    'old_photo' => $oldPhoto,
                    'new_filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize()
                ]);

                // 1. Buat folder jika belum ada (sama seperti store)
                $folderPath = storage_path('app/public/hewan');
                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0755, true);
                    \Log::info('UPDATE - Created directory:', [$folderPath]);
                }

                // 2. Pindahkan file secara manual (sama seperti store)
                $destinationPath = $folderPath . '/' . $filename;

                // Coba pindahkan
                if ($file->move($folderPath, $filename)) {
                    \Log::info('✅ UPDATE - File moved successfully to:', [$destinationPath]);
                    $validated['foto'] = $filename;

                    // 3. Verifikasi (sama seperti store)
                    if (file_exists($destinationPath)) {
                        \Log::info('✅ UPDATE - File verified:', [
                            'path' => $destinationPath,
                            'size' => filesize($destinationPath)
                        ]);
                    }

                    // 4. Hapus foto lama JIKA ADA
                    if ($oldPhoto && $oldPhoto !== $filename) {
                        $oldPhotoPath = $folderPath . '/' . $oldPhoto;

                        if (file_exists($oldPhotoPath)) {
                            if (unlink($oldPhotoPath)) {
                                \Log::info('🗑️ UPDATE - Old photo deleted:', [$oldPhoto]);
                            } else {
                                \Log::warning('UPDATE - Failed to delete old photo:', [$oldPhoto]);
                            }
                        } else {
                            \Log::info('UPDATE - Old photo not found:', [$oldPhoto]);
                        }
                    }
                } else {
                    \Log::error('❌ UPDATE - Failed to move file');
                    $validated['foto'] = $oldPhoto; // Tetap gunakan foto lama
                }
            } else {
                // Jika tidak upload foto baru
                $validated['foto'] = $oldPhoto;

                // Handle jika user ingin menghapus foto (ada input remove_foto)
                if ($request->has('remove_foto') && $request->input('remove_foto') == '1') {
                    $validated['foto'] = null;

                    // Hapus file fisik
                    if ($oldPhoto) {
                        $oldPhotoPath = storage_path('app/public/hewan/' . $oldPhoto);
                        if (file_exists($oldPhotoPath)) {
                            if (unlink($oldPhotoPath)) {
                                \Log::info('🗑️ UPDATE - Photo removed by user:', [$oldPhoto]);
                            }
                        }
                    }
                }
            }

            // Update data ke database
            $ketersediaanHewan->update($validated);

            DB::commit();

            \Log::info('✅ UPDATE - Data updated successfully:', [
                'id' => $ketersediaanHewan->id,
                'new_photo' => $validated['foto'] ?? 'null'
            ]);

            return redirect()->route('admin.ketersediaan-hewan.index')
                ->with('success', 'Data ketersediaan hewan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded file if error (sama seperti store)
            if (isset($filename) && file_exists(storage_path('app/public/hewan/' . $filename))) {
                unlink(storage_path('app/public/hewan/' . $filename));
                \Log::info('UPDATE - Rollback: Deleted uploaded file:', [$filename]);
            }

            \Log::error('❌ UPDATE - Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data. Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(KetersediaanHewan $ketersediaanHewan)
    {
        try {

            if ($ketersediaanHewan->foto) {

                $path = 'hewan/' . $ketersediaanHewan->foto;

                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            $ketersediaanHewan->delete();

            return redirect()
                ->route('admin.ketersediaan-hewan.index')
                ->with('success', 'Data ketersediaan hewan berhasil dihapus.');
        } catch (\Throwable $e) {

            return redirect()
                ->route('admin.ketersediaan-hewan.index')
                ->with('error', 'Gagal menghapus data.');
        }
    }
}
