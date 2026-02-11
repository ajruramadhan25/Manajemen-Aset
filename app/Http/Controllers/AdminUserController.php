<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class AdminUserController extends Controller
{
    /**
     * List admin and karyawan accounts.
     */
    public function index(Request $request): View
    {
        $query = User::query()->orderBy('name');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        if ($request->filled('role') && in_array($request->role, ['admin', 'karyawan'], true)) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(10)->appends($request->only(['search', 'role']));

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show form to create admin account.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a new admin account.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.create')->with('success', 'Akun admin berhasil dibuat.');
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menonaktifkan akun sendiri.');
        }

        if (!in_array($user->role, ['admin', 'karyawan'], true)) {
            return back()->with('error', 'Tidak bisa mengubah status role ini.');
        }

        $user->update(['is_active' => !$user->is_active]);

        return back()->with('success', 'Status akun berhasil diperbarui.');
    }

    /**
     * Delete user account.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        if (!in_array($user->role, ['admin', 'karyawan'], true)) {
            return back()->with('error', 'Tidak bisa menghapus role ini.');
        }

        $user->delete();

        return back()->with('success', 'Akun berhasil dihapus.');
    }
}
