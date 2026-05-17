<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Order;
use App\Models\Pelaksanaan;
use Illuminate\Http\Request;
use App\Models\KetersediaanHewan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $orders = Order::with('user', 'ketersediaanHewan')
            ->paginate(5);

        return view('admin.order.index', compact('orders', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // Di Controller store() method
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipe_pendaftaran' => 'required|in:transfer,kirim langsung',
            'ketersediaan_hewan_id' => 'required_if:tipe_pendaftaran,transfer|exists:ketersediaan_hewan,id',
            'bank_id' => 'required_if:tipe_pendaftaran,transfer|exists:bank_penerima,id',
            'jenis_hewan' => 'required_if:tipe_pendaftaran,kirim langsung|string|max:100',
            'berat_kirim' => 'required_if:tipe_pendaftaran,kirim langsung|numeric|min:1',
            'total_hewan' => 'required|integer|min:1|max:1',
            'bukti_pembayaran' => 'required_if:tipe_pendaftaran,transfer|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        DB::beginTransaction();

        try {
            /** 1. Ambil pelaksanaan aktif */
            $pelaksanaanAktif = Pelaksanaan::where('status', 'Active')->first();

            if (!$pelaksanaanAktif) {
                return back()->withInput()
                    ->with('error', 'Tidak ada pelaksanaan aktif.');
            }

            /** 2. Siapkan data dasar */
            $data = [
                'user_id' => auth()->id(),
                'tipe_pendaftaran' => $validated['tipe_pendaftaran'],
                'total_hewan' => $validated['total_hewan'],
                'pelaksanaan_id' => $pelaksanaanAktif->id,
                'status' => $validated['tipe_pendaftaran'] === 'transfer'
                    ? 'menunggu verifikasi'
                    : 'disetujui',
            ];

            /** 3. LOGIKA TRANSFER */
            if ($validated['tipe_pendaftaran'] === 'transfer') {

                $hewan = KetersediaanHewan::findOrFail($validated['ketersediaan_hewan_id']);

                if ($hewan->jumlah < $validated['total_hewan']) {
                    return back()->withInput()
                        ->with('error', 'Stok hewan tidak mencukupi.');
                }

                $data += [
                    'ketersediaan_hewan_id' => $hewan->id,
                    'jenis_hewan' => $hewan->jenis_hewan,
                    'berat_hewan' => $hewan->bobot,
                    'perkiraan_daging' => $hewan->bobot * 0.4,
                    'total_harga' => $hewan->harga,
                    'bank_id' => $validated['bank_id'],
                ];

                if ($request->hasFile('bukti_pembayaran')) {
                    $data['bukti_pembayaran'] = $request
                        ->file('bukti_pembayaran')
                        ->store('bukti_pembayaran', 'public');
                }

                $hewan->decrement('jumlah');
            }

            /** 4. LOGIKA KIRIM LANGSUNG */
            else {
                $data += [
                    'ketersediaan_hewan_id' => null,
                    'bank_id' => null,
                    'bukti_pembayaran' => null,
                    'jenis_hewan' => $validated['jenis_hewan'],
                    'berat_hewan' => $validated['berat_kirim'],
                    'perkiraan_daging' => $validated['berat_kirim'] * 0.4,
                    'total_harga' => 0,
                ];
            }

            /** 5. SIMPAN ORDER */
            $order = Order::create($data);

            DB::commit();

            return redirect()->route('peserta.dashboard')
                ->with('success', 'Pendaftaran berhasil!');
        } catch (\Throwable $e) {
            DB::rollBack();

            \Log::error('Store Order Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()
                ->with('error', 'Gagal menyimpan data.');
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

    // Di OrderController.php
    public function verify(Request $request, Order $order)
    {
        $request->validate([
            'alasan_penolakan' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $order->update([
                'status' => 'disetujui', // Match dengan ENUM value
                'alasan_penolakan' => $request->alasan_penolakan,
                'verified_at' => now(),
                'verified_by' => auth()->id(),
                'alasan_penolakan' => $request->alasan_penolakan,
                'rejected_at' => null,
                'rejected_by' => null,
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Order berhasil disetujui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menyetujui order: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Order $order)
    {

        $request->validate([
            'alasan_penolakan' => 'required|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            // Jika order transfer, kembalikan stok
            if ($order->tipe_pendaftaran === 'transfer' && $order->ketersediaan_hewan_id) {
                $hewan = KetersediaanHewan::find($order->ketersediaan_hewan_id);
                if ($hewan) {
                    $hewan->increment('jumlah', $order->total_hewan);

                    if ($hewan->status == 'habis') {
                        $hewan->update(['status' => 'tersedia']);
                    }
                }
            }

            $order->update([
                'status' => 'ditolak', // Match dengan ENUM value
                'alasan_penolakan' => $request->alasan_penolakan,
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
                'verification_note' => null,
                'verified_at' => null,
                'verified_by' => null,
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Order berhasil ditolak!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menolak order: ' . $e->getMessage());
        }
    }

    // route baru
    public function verifikasi()
    {
        $user = auth()->user();

        // Hanya tampilkan order dengan status 'menunggu_verifikasi'
        $orders = Order::with(['user', 'ketersediaanHewan'])
            ->where('status', 'menunggu verifikasi')
            ->latest()
            ->paginate(10);

        return view('admin/order/persetujuan', compact('orders', 'user'));
    }

    // Optional: untuk status lainnya
    public function approved()
    {
        $user = auth()->user();

        $orders = Order::with(['user', 'ketersediaanHewan'])
            ->where('status', 'disetujui')
            ->latest()
            ->paginate(10);

        return view('admin/order/approved', compact('orders', 'user'));
    }

    public function rejected()
    {
        $user = auth()->user();

        $orders = Order::with(['user',  'ketersediaanHewan'])
            ->where('status', 'ditolak')
            ->whereNotNull('alasan_penolakan')
            ->latest()
            ->paginate(10);

        return view('admin/order/rejected', compact('orders', 'user'));
    }
}
