<?php

namespace App\Services;

use App\Models\KetersediaanHewan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Exception;

class KetersediaanHewanService
{
    /**
     * Create a new animal availability record.
     */
    public function createKetersediaan(array $data, ?UploadedFile $foto): KetersediaanHewan
    {
        DB::beginTransaction();
        try {
            if ($foto && $foto->isValid()) {
                $data['foto'] = $this->handlePhotoUpload($foto);
            }

            $ketersediaan = KetersediaanHewan::create($data);

            DB::commit();
            return $ketersediaan;
        } catch (Exception $e) {
            DB::rollBack();
            if (isset($data['foto'])) {
                $this->deletePhoto($data['foto']);
            }
            throw $e;
        }
    }

    /**
     * Update an existing animal availability record.
     */
    public function updateKetersediaan(KetersediaanHewan $hewan, array $data, ?UploadedFile $foto, bool $removeFoto): KetersediaanHewan
    {
        DB::beginTransaction();
        try {
            $oldPhoto = $hewan->foto;

            if ($foto && $foto->isValid()) {
                $data['foto'] = $this->handlePhotoUpload($foto);
                
                if ($oldPhoto) {
                    $this->deletePhoto($oldPhoto);
                }
            } else {
                $data['foto'] = $oldPhoto;
                
                if ($removeFoto) {
                    $data['foto'] = null;
                    if ($oldPhoto) {
                        $this->deletePhoto($oldPhoto);
                    }
                }
            }

            $hewan->update($data);

            DB::commit();
            return $hewan;
        } catch (Exception $e) {
            DB::rollBack();
            if (isset($data['foto']) && $data['foto'] !== $hewan->foto) {
                $this->deletePhoto($data['foto']);
            }
            throw $e;
        }
    }

    /**
     * Delete an animal availability record.
     */
    public function deleteKetersediaan(KetersediaanHewan $hewan): void
    {
        if ($hewan->foto) {
            $this->deletePhoto($hewan->foto);
        }
        $hewan->delete();
    }

    /**
     * Helper to handle photo upload logic.
     */
    private function handlePhotoUpload(UploadedFile $file): string
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $folderPath = storage_path('app/public/hewan');
        
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }
        
        if (!$file->move($folderPath, $filename)) {
            throw new Exception('Failed to move uploaded file');
        }

        return $filename;
    }

    /**
     * Helper to delete physical photo file.
     */
    private function deletePhoto(string $filename): void
    {
        $path = storage_path('app/public/hewan/' . $filename);
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
