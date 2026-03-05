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

        $this->createSupportRequest($request, [
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'message' => $validated['message'],
            'source_page' => url()->previous(),
        ]);

        return back()->with('success', 'Your request has been sent. Our team will contact you soon.');
    }

    public function storeLongTerm(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'inquiry_type' => ['required', 'in:quick,custom'],
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:40', 'required_without:email'],
            'email' => ['nullable', 'email', 'max:180', 'required_without:phone'],
            'message' => ['nullable', 'string', 'max:3000'],
            'category' => ['nullable', 'string', 'max:120'],
            'start_date' => ['nullable', 'date'],
            'duration' => ['nullable', 'string', 'max:60'],
            'company' => ['nullable', 'string', 'max:120'],
        ]);

        if (($validated['inquiry_type'] ?? '') === 'quick') {
            $quickSummary = sprintf(
                "Quick long-term inquiry\nCategory: %s\nStart Date: %s\nDuration: %s",
                (string) ($validated['category'] ?? '-'),
                (string) ($validated['start_date'] ?? '-'),
                (string) ($validated['duration'] ?? '-')
            );

            $this->createSupportRequest($request, [
                'name' => $validated['name'],
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'] ?? null,
                'message' => $quickSummary,
                'source_page' => 'long-term-rentals:quick',
            ]);

            return back()->with('success', 'Quick inquiry submitted. Our team will contact you soon.');
        }

        $customMessage = trim((string) ($validated['message'] ?? ''));
        $company = trim((string) ($validated['company'] ?? ''));
        if ($company !== '') {
            $customMessage = "Company: {$company}\n" . $customMessage;
        }

        $this->createSupportRequest($request, [
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'message' => $customMessage !== '' ? $customMessage : 'Custom long-term quote request.',
            'source_page' => 'long-term-rentals:custom',
        ]);

        return back()->with('success', 'Custom quote request submitted. Our team will contact you soon.');
    }

    private function createSupportRequest(Request $request, array $data): void
    {
        CustomerSupportRequest::create([
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'message' => $data['message'],
            'source_page' => $data['source_page'] ?? url()->previous(),
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1024),
        ]);
    }
}
