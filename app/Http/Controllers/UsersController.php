<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        $users = User::orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin/users.index', compact('users', 'user'));
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
        //
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
        $request->validate([
            'role' => 'required|in:user,admin,admin_kurban', // Sesuaikan dengan role yang tersedia
        ]);

        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);

            // Cek jika user yang diedit adalah super admin
            if ($user->hasRole('admin_kurban')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat mengubah role Super Admin.'
                ], 403);
            }

            // Cek jika mencoba mengubah role sendiri
            if ($user->id === auth()->id() && $request->role !== 'admin_kurban') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat mengubah role sendiri.'
                ], 403);
            }

            $oldRole = $user->role;
            $user->role = $request->role;
            $user->save();

            // Log perubahan role
            Log::info('User role updated', [
                'user_id' => $user->id,
                'updated_by' => auth()->id(),
                'old_role' => $oldRole,
                'new_role' => $request->role
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Role pengguna berhasil diperbarui.',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating user role: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui role.'
            ], 5800);
        }
    }


    /**
     * Remove the specified user.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);

            // Cek jika user yang dihapus adalah super admin atau diri sendiri
            if ($user->hasRole('super_admin')) {
                return back()->with('error', 'Tidak dapat menghapus Super Admin.');
            }

            if ($user->id === auth()->id()) {
                return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
            }

            $user->delete();

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting user: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat menghapus pengguna.');
        }
    }
}
