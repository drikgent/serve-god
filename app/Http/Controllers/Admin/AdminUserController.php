<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    private const ADMIN_ROLES = ['super_admin', 'editor'];

    public function index(): View
    {
        return view('admin.admins.index', [
            'admins' => User::whereIn('role', self::ADMIN_ROLES)->latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(self::ADMIN_ROLES)],
        ]);

        User::create([
            ...$data,
            'password' => Hash::make($data['password']),
            'is_active' => true,
        ]);

        return redirect()->route('admin.admins.index')->with('status', 'Admin account created.');
    }

    public function update(Request $request, User $admin): RedirectResponse
    {
        abort_unless(in_array($admin->role, self::ADMIN_ROLES, true), 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $admin->update([
            'name' => $data['name'],
        ]);

        return redirect()->route('admin.admins.index')->with('status', 'Admin name updated.');
    }
}
