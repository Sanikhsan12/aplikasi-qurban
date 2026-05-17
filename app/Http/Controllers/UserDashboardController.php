<?php

namespace App\Http\Controllers;

use App\Models\BankPenerima;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Pelaksanaan;
use Illuminate\Http\Request;
use App\Models\Penyembelihan;
use App\Models\DistribusiDaging;
use App\Models\KetersediaanHewan;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

// --------------------------- Data untuk menampilkan ketersediaan hewan ---------------------------
        $ketersediaan_hewan = KetersediaanHewan::where('jumlah', '>', 0)
            ->orderBy('jenis_hewan')
            ->get();
// ----------------------- Data untuk menampilkan ketersediaan hewan selesai ---------------------------




// ---------------- Data untuk menampilkan penyembelihan hewan user ---------------------------
        $penyembelihan = Penyembelihan::whereHas('order', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
            // ->with(['hewan.user', 'hewan.detail.ketersediaan'])
            ->get();
// ---------------- Data untuk menampilkan penyembelihan hewan user selesai ---------------------------





// ---------------------------- Data untuk menampilkan order user ---------------------------
        $orders = Auth::user()->orders()->latest()->get();
// -------------------------- Data untuk menampilkan order user selesai ---------------------------




// ---------------------------- Cek status pendaftaran ---------------------------
        $pendaftaran_dibuka = true; 
// -------------------------- Cek status pendaftaran selesai ---------------------------




// ---------------- Data untuk menampilkan pelaksanaan kurban ---------------------------
        $pelaksanaanKurban = Pelaksanaan::latest('id')->take(1)->get();

        $pelaksanaan = Pelaksanaan::latest('id')->first();

        $today = Carbon::today();

        $isOpen = $today->between(
            Carbon::parse($pelaksanaan->Tanggal_Pendaftaran),
            Carbon::parse($pelaksanaan->Tanggal_Penutupan)
        );
// ---------------- Data untuk menampilkan pelaksanaan kurban selesai ---------------------------



// ---------------- Data untuk menampilkan detail pembayaran user ---------------------------
        $detailPembayaran = Order::where('user_id', $userId)
            ->with('user')
            ->get();
// ---------------- Data untuk menampilkan detail pembayaran user selesai ---------------------------



//  -------------------------------- Distribusi Daging ----------------------------------------
        $distribusi = DistribusiDaging::with([
            'pelaksanaan',
            'dokumentasi' 
        ])
            ->latest()
            ->paginate(5);
//  ----------------------------- Distribusi Daging Selesai ------------------------------------



//  -------------------------------- Bank Penerima ----------------------------------------
        $bankPenerima = BankPenerima::paginate(5);
//  ----------------------------- Bank Penerima Selesai ------------------------------------

        return view('user/dashboard', compact(
            'ketersediaan_hewan',
            'orders',
            'pendaftaran_dibuka',
            'pelaksanaanKurban',
            'isOpen',
            'pelaksanaan',
            'detailPembayaran',
            'penyembelihan',
            'distribusi',
            'bankPenerima'
        ));
    }
}
