<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DeteksiUangController extends Controller
{
    public function detect(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        try {
            $imagePath = $request->file('image')->path();

            $response = Http::timeout(30)
                ->attach(
                    'image',
                    file_get_contents($imagePath),
                    'image.jpg'
                )
                ->post('http://ml-server:5000/detect');

            if ($response->failed()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal menghubungi server deteksi.'
                ], 500);
            }

            return response()->json($response->json());
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
