<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::query()->orderByDesc('id')->get();

        $customers = Customer::query()->orderBy('name')->get();

        return view('users.index', compact('users', 'customers'));
    }

    public function store(Request $request)
    {
        $supportsCustomerLink = Schema::hasColumn('users', 'customer_id');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['super_admin', 'admin', 'customer', 'partner', 'customer_portal', 'partner_applicant'])],
            'customer_id' => $supportsCustomerLink ? ['nullable', 'exists:customers,id'] : ['nullable'],
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

        if ($supportsCustomerLink && $data['role'] === 'customer_portal' && empty($data['customer_id'])) {
            return back()->withErrors([
                'customer_id' => 'Customer Portal users must be linked to a customer.',
            ])->withInput();
        }

        $payload = [
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'role' => $data['role'],
            'partner_share_percentage' => $data['role'] === 'partner' ? round((float) ($data['partner_share_percentage'] ?? 80), 2) : 0,
            'admin_share_percentage' => $data['role'] === 'partner' ? round((float) ($data['admin_share_percentage'] ?? 20), 2) : 100,
            'password' => Hash::make($data['password']),
        ];

        if ($supportsCustomerLink) {
            $payload['customer_id'] = $data['role'] === 'customer_portal' ? ($data['customer_id'] ?? null) : null;
        }

        User::create($payload);

        return back()->with('success', 'User created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $supportsCustomerLink = Schema::hasColumn('users', 'customer_id');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['super_admin', 'admin', 'customer', 'partner', 'customer_portal', 'partner_applicant'])],
            'customer_id' => $supportsCustomerLink ? ['nullable', 'exists:customers,id'] : ['nullable'],
            'partner_share_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'admin_share_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if ($request->user()->id === $user->id && in_array($user->role, ['admin', 'super_admin'], true) && !in_array($data['role'], ['admin', 'super_admin'], true)) {
            return back()->withErrors(['role' => 'You cannot remove your own admin access role.']);
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

        if ($supportsCustomerLink && $data['role'] === 'customer_portal' && empty($data['customer_id'])) {
            return back()->withErrors([
                'customer_id' => 'Customer Portal users must be linked to a customer.',
            ])->withInput();
        }

        $update = [
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'role' => $data['role'],
            'partner_share_percentage' => $data['role'] === 'partner' ? round((float) ($data['partner_share_percentage'] ?? ($user->partner_share_percentage ?? 80)), 2) : 0,
            'admin_share_percentage' => $data['role'] === 'partner' ? round((float) ($data['admin_share_percentage'] ?? ($user->admin_share_percentage ?? 20)), 2) : 100,
        ];

        if ($supportsCustomerLink) {
            $update['customer_id'] = $data['role'] === 'customer_portal' ? ($data['customer_id'] ?? null) : null;
        }

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
