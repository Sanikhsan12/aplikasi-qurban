<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\DanaDKM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Basic Stats
        $total_peserta = Order::count();
        $total_hewan = Order::sum('total_hewan');
        $total_berat_daging = Order::sum('perkiraan_daging');
        $total_pendapatan = Order::where('status', 'disetujui')->sum('total_harga');

        // Payment Stats
        $orders = [
            'total' => Order::count(),
            'disetujui' => [
                'count' => Order::where('status', 'disetujui')->count(),
                'total_harga' => Order::where('status', 'disetujui')->sum('total_harga')
            ],
            'menunggu_verifikasi' => [
                'count' => Order::where('status', 'menunggu verifikasi')->count(),
                'total_total_harga' => Order::where('status', 'menunggu verifikasi')->sum('total_harga')
            ],
            'ditolak' => [
                'count' => Order::where('status', 'ditolak')->count(),
                'total_total_harga' => Order::where('status', 'ditolak')->sum('total_harga')
            ]
        ];

        // Calculate percentages
        $paymentStats = [];
        foreach (['disetujui', 'menunggu verifikasi', 'ditolak'] as $status) {
            $count = $orders[$status == 'menunggu verifikasi' ? 'menunggu_verifikasi' : $status]['count'] ?? 0;
            $percent = $orders['total'] > 0 ? round(($count / $orders['total']) * 100, 1) : 0;
            $paymentStats[$status] = [
                'count' => $count,
                'percent' => $percent
            ];
        }

        // Animal Type Distribution
        $hewan_stats = Order::select('jenis_hewan', DB::raw('COUNT(*) as count'))
            ->groupBy('jenis_hewan')
            ->get()
            ->map(function ($item, $index) use ($total_hewan) {
                $colors = ['primary', 'success', 'info', 'warning', 'danger'];
                $icons = ['fas fa-sheep', 'fas fa-goat', 'fas fa-cow', 'fas fa-kiwi-bird', 'fas fa-horse'];

                return [
                    'label' => ucfirst($item->jenis_hewan),
                    'count' => $item->count,
                    'percentage' => $total_hewan > 0 ? round(($item->count / $total_hewan) * 100, 1) : 0,
                    'color' => $colors[$index % count($colors)] ?? 'primary',
                    'icon' => $icons[$index % count($icons)] ?? 'fas fa-paw'
                ];
            });

        // Chart data
        $chart_labels = $hewan_stats->pluck('label')->toArray();
        $chart_data = $hewan_stats->pluck('count')->toArray();

        // Recent Activities
        $new_user = User::latest('created_at')
            ->first()
            ?->created_at
            ?->diffForHumans();

        $new_order = Order::latest('created_at')
            ->first()
            ?->created_at
            ?->diffForHumans();

        $recent_activities = [
            [
                // dari tabel user
                'title' => 'Pendaftaran peserta baru',
                'time' => $new_user,
                'icon' => 'fas fa-user-plus',
                'color' => 'success'
            ],
            [
                // dari tabel order
                'title' => 'pembelian hewan',
                'time' => $new_order,
                'icon' => 'fas fa-check-circle',
                'color' => 'primary'
            ],
        ];

        // Target Achievement
        $target_peserta = 500; // Example target
        $actual_peserta = User::where('role', 'peserta_kurban')->count();

        $target_hewan = 200; // Example target
        $actual_hewan = Order::where('status', 'disetujui')->count();

        $target_pendapatan = 1000000000; // Example target
        $actual_pendapatan = $total_pendapatan;

        $peserta_achievement = $target_peserta > 0 ? min(round(($total_peserta / $target_peserta) * 100, 1), 100) : 0;
        $hewan_achievement = $target_hewan > 0 ? min(round(($total_hewan / $target_hewan) * 100, 1), 100) : 0;
        $pendapatan_achievement = $target_pendapatan > 0 ? min(round(($total_pendapatan / $target_pendapatan) * 100, 1), 100) : 0;
        $achievement_rate = round(($peserta_achievement + $hewan_achievement + $pendapatan_achievement) / 3, 1);

        return view('admin.dashboard', compact(
            'user',
            'new_user',
            'total_peserta',
            'total_hewan',
            'total_berat_daging',
            'total_pendapatan',
            'orders',
            'paymentStats',
            'hewan_stats',
            'chart_labels',
            'chart_data',
            'recent_activities',
            'target_peserta',
            'actual_peserta',
            'target_hewan',
            'actual_hewan',
            'target_pendapatan',
            'actual_pendapatan',
            'peserta_achievement',
            'hewan_achievement',
            'pendapatan_achievement',
            'achievement_rate',
        ));
    }
}
