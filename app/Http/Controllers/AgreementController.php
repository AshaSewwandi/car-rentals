<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgreementController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $agreementsQuery = Agreement::query()
            ->with(['vehicle', 'customer'])
            ->latest();

        if ($user?->isCustomerPortal()) {
            $customerId = $user->customer_id;
            if (!$customerId) {
                return view('agreements.index', [
                    'vehicles' => collect(),
                    'customers' => collect(),
                    'agreements' => collect(),
                ]);
            }
            $agreementsQuery->where('customer_id', $customerId);
        }

        $agreements = $agreementsQuery->get();

        if ($user?->isCustomerPortal()) {
            $vehicleIds = $agreements->pluck('vehicle_id')->unique()->filter()->values();
            $vehicles = $vehicleIds->isEmpty()
                ? collect()
                : Vehicle::query()->whereIn('id', $vehicleIds)->orderBy('name')->get();
            $customers = Customer::query()->where('id', $user->customer_id)->orderBy('name')->get();
        } else {
            $vehicles = Vehicle::query()->orderBy('name')->get();
            $customers = Customer::query()->orderBy('name')->get();
        }

        return view('agreements.index', compact('vehicles', 'customers', 'agreements'));
    }

    public function store(Request $request)
    {
        $data = $this->validateAgreement($request);

        if ($request->hasFile('agreement_file')) {
            $data['file_path'] = $request->file('agreement_file')->store('agreements', 'public');
        }

        Agreement::create($data);

        return back()->with('success', 'Agreement saved successfully.');
    }

    public function update(Request $request, Agreement $agreement)
    {
        $data = $this->validateAgreement($request);

        if ($request->hasFile('agreement_file')) {
            if ($agreement->file_path) {
                Storage::disk('public')->delete($agreement->file_path);
            }
            $data['file_path'] = $request->file('agreement_file')->store('agreements', 'public');
        }

        $agreement->update($data);

        return back()->with('success', 'Agreement updated successfully.');
    }

    public function destroy(Agreement $agreement)
    {
        if ($agreement->file_path) {
            Storage::disk('public')->delete($agreement->file_path);
        }

        $agreement->delete();

        return back()->with('success', 'Agreement deleted successfully.');
    }

    private function validateAgreement(Request $request): array
    {
        return $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'customer_id' => ['required', 'exists:customers,id'],
            'agreement_no' => ['nullable', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'monthly_rent' => ['required', 'numeric', 'min:0'],
            'deposit' => ['nullable', 'numeric', 'min:0'],
            'note' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,ended'],
            'agreement_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);
    }
}
