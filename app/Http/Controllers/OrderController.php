<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Kontrak;
use App\Models\Sertifikat;
use App\Models\Pelaksanaan;
use Illuminate\Http\Request;
use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $user = auth()->user();
        $orders = Order::with('user', 'ketersediaanHewan')->paginate(5);

        return view('admin.order.index', compact('orders', 'user'));
    }

    public function store(StoreOrderRequest $request)
    {
        try {
            $order = $this->orderService->createOrder($request->validated(), auth()->id());

            if ($request->tipe_pendaftaran === 'transfer') {
                return redirect()->route('peserta.payment.show', $order->id)
                    ->with('success', 'Pesanan berhasil dibuat! Silakan lanjutkan ke pembayaran.');
            }

            return redirect()->route('peserta.dashboard')
                ->with('success', 'Pendaftaran kirim langsung berhasil!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage() ?: 'Gagal menyimpan data.');
        }
    }

    public function verify(Request $request, Order $order)
    {
        $request->validate([
            'alasan_penolakan' => 'nullable|string|max:500'
        ]);

        try {
            $this->orderService->verifyOrder($order, $request->alasan_penolakan, auth()->id());
            return redirect()->back()->with('success', 'Order berhasil disetujui!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyetujui order: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Order $order)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:500'
        ]);

        try {
            $this->orderService->rejectOrder($order, $request->alasan_penolakan, auth()->id());
            return redirect()->back()->with('success', 'Order berhasil ditolak!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menolak order: ' . $e->getMessage());
        }
    }

    public function verifikasi()
    {
        $user = auth()->user();
        $orders = Order::with(['user', 'ketersediaanHewan'])
            ->where('status', 'menunggu verifikasi')
            ->latest()
            ->paginate(10);

        return view('admin/order/persetujuan', compact('orders', 'user'));
    }

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
