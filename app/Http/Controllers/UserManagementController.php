<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::query()->orderByDesc('id')->get();

        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['admin', 'customer', 'partner'])],
            'partner_share_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'admin_share_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($data['role'] === 'partner') {
            $partnerShare = round((float) ($data['partner_share_percentage'] ?? 80), 2);
            $adminShare = round((float) ($data['admin_share_percentage'] ?? 20), 2);

            if (round($partnerShare + $adminShare, 2) !== 100.0) {
                return back()->withErrors([
                    'partner_share_percentage' => 'Partner and admin percentages must total 100%.',
                ])->withInput();
            }
        }

        User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'role' => $data['role'],
            'partner_share_percentage' => $data['role'] === 'partner' ? round((float) ($data['partner_share_percentage'] ?? 80), 2) : 0,
            'admin_share_percentage' => $data['role'] === 'partner' ? round((float) ($data['admin_share_percentage'] ?? 20), 2) : 100,
            'password' => Hash::make($data['password']),
        ]);

        return back()->with('success', 'User created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'customer', 'partner'])],
            'partner_share_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'admin_share_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if ($request->user()->id === $user->id && $user->role === 'admin' && $data['role'] !== 'admin') {
            return back()->withErrors(['role' => 'You cannot remove your own admin role.']);
        }

        if ($data['role'] === 'partner') {
            $partnerShare = round((float) ($data['partner_share_percentage'] ?? ($user->partner_share_percentage ?? 80)), 2);
            $adminShare = round((float) ($data['admin_share_percentage'] ?? ($user->admin_share_percentage ?? 20)), 2);

            if (round($partnerShare + $adminShare, 2) !== 100.0) {
                return back()->withErrors([
                    'partner_share_percentage' => 'Partner and admin percentages must total 100%.',
                ])->withInput();
            }
        }

        $update = [
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'role' => $data['role'],
            'partner_share_percentage' => $data['role'] === 'partner' ? round((float) ($data['partner_share_percentage'] ?? ($user->partner_share_percentage ?? 80)), 2) : 0,
            'admin_share_percentage' => $data['role'] === 'partner' ? round((float) ($data['admin_share_percentage'] ?? ($user->admin_share_percentage ?? 20)), 2) : 100,
        ];

        if (!empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }

        $user->update($update);

        return back()->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            return back()->withErrors(['user' => 'You cannot delete your own account.']);
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }
}
