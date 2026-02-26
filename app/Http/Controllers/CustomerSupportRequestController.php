<?php

namespace App\Http\Controllers;

use App\Models\CustomerSupportRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerSupportRequestController extends Controller
{
    public function index(): View
    {
        $supportRequests = CustomerSupportRequest::query()
            ->latest()
            ->paginate(15);

        return view('support-requests.index', compact('supportRequests'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:40', 'required_without:email'],
            'email' => ['nullable', 'email', 'max:180', 'required_without:phone'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        CustomerSupportRequest::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'message' => $validated['message'],
            'source_page' => url()->previous(),
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1024),
        ]);

        return back()->with('success', 'Your request has been sent. Our team will contact you soon.');
    }
}
