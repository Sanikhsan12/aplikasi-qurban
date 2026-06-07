<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Kontrak;
use App\Models\Sertifikat;
use App\Models\Pelaksanaan;
use Illuminate\Http\Request;
use App\Models\KetersediaanHewan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipe_pendaftaran' => 'required|in:transfer,kirim langsung',
            'ketersediaan_hewan_id' => 'required_if:tipe_pendaftaran,transfer|exists:ketersediaan_hewan,id',
            'jenis_hewan' => 'required_if:tipe_pendaftaran,kirim langsung|string|max:100',
            'berat_kirim' => 'required_if:tipe_pendaftaran,kirim langsung|numeric|min:1',
            'total_hewan' => 'required|integer|min:1|max:1',
            'peserta_2' => 'nullable|string|max:255',
            'peserta_3' => 'nullable|string|max:255',
            'peserta_4' => 'nullable|string|max:255',
            'peserta_5' => 'nullable|string|max:255',
            'peserta_6' => 'nullable|string|max:255',
            'peserta_7' => 'nullable|string|max:255',
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

            $jenisHewan = null;

            /** 3. LOGIKA TRANSFER */
            if ($validated['tipe_pendaftaran'] === 'transfer') {

                $hewan = KetersediaanHewan::findOrFail($validated['ketersediaan_hewan_id']);

                if ($hewan->jumlah < $validated['total_hewan']) {
                    return back()->withInput()
                        ->with('error', 'Stok hewan tidak mencukupi.');
                }

                $jenisHewan = $hewan->jenis_hewan;

                $data += [
                    'ketersediaan_hewan_id' => $hewan->id,
                    'jenis_hewan' => $jenisHewan,
                    'berat_hewan' => $hewan->bobot,
                    'perkiraan_daging' => $hewan->bobot * 0.4,
                    'total_harga' => $hewan->harga,
                    'bank_id' => null,
                ];

                $hewan->decrement('jumlah');
            }

            /** 4. LOGIKA KIRIM LANGSUNG */
            else {
                $jenisHewan = $validated['jenis_hewan'];

                $data += [
                    'ketersediaan_hewan_id' => null,
                    'bank_id' => null,
                    'bukti_pembayaran' => null,
                    'jenis_hewan' => $jenisHewan,
                    'berat_hewan' => $validated['berat_kirim'],
                    'perkiraan_daging' => $validated['berat_kirim'] * 0.4,
                    'total_harga' => 0,
                ];
            }

            /** 5. Validasi peserta SAPI */
            $isSapi = strcasecmp($jenisHewan, 'Sapi') === 0;

            if ($isSapi) {
                for ($i = 2; $i <= 7; $i++) {
                    $namaField = "peserta_{$i}";
                    if (empty($validated[$namaField])) {
                        return back()->withInput()
                            ->with('error', "Nama peserta {$i} wajib diisi untuk hewan Sapi.");
                    }
                }
            }

            /** 6. SIMPAN ORDER */
            $order = Order::create($data);

            /** 7. SIMPAN PESERTA */
            $order->peserta()->create([
                'nama_peserta' => auth()->user()->name,
                'is_buyer' => true,
            ]);

            if ($isSapi) {
                for ($i = 2; $i <= 7; $i++) {
                    $order->peserta()->create([
                        'nama_peserta' => $validated["peserta_{$i}"],
                        'is_buyer' => false,
                    ]);
                }
            }

            DB::commit();

            if ($validated['tipe_pendaftaran'] === 'transfer') {
                return redirect()->route('peserta.payment.show', $order->id)
                    ->with('success', 'Pesanan berhasil dibuat! Silakan lanjutkan ke pembayaran.');
            }

            return redirect()->route('peserta.dashboard')
                ->with('success', 'Pendaftaran kirim langsung berhasil!');
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
                'status' => 'disetujui',
                'alasan_penolakan' => $request->alasan_penolakan,
                'verified_at' => now(),
                'verified_by' => auth()->id(),
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
                'status' => 'ditolak',
                'alasan_penolakan' => $request->alasan_penolakan,
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
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

    public function invoice(Order $order)
    {
        $kontrak = $order->kontrak;

        if ($kontrak && $kontrak->file_path) {
            $path = storage_path('app/public/' . $kontrak->file_path);

            if (file_exists($path)) {
                return response()->download($path, 'kontrak_' . $order->id . '.pdf');
            }
        }

        $pelaksanaan = Pelaksanaan::find($order->pelaksanaan_id);
        $peserta = $order->peserta;

        $pdf = Pdf::loadView('pdf.invoice', [
            'order' => $order,
            'pelaksanaan' => $pelaksanaan,
            'peserta' => $peserta,
        ]);

        return $pdf->download('invoice_' . $order->id . '.pdf');
    }

    public function downloadSertifikat(Sertifikat $sertifikat)
    {
        if ($sertifikat->file_path) {
            $path = storage_path('app/public/' . $sertifikat->file_path);

            if (file_exists($path)) {
                return response()->download($path, 'sertifikat_' . $sertifikat->id . '.pdf');
            }
        }

        $order = $sertifikat->order;
        $pelaksanaan = Pelaksanaan::find($order->pelaksanaan_id);

        $pdf = Pdf::loadView('pdf.sertifikat', [
            'sertifikat' => $sertifikat,
            'order' => $order,
            'pelaksanaan' => $pelaksanaan,
        ]);

        return $pdf->download('sertifikat_' . $sertifikat->id . '.pdf');
    }
}
