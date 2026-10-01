<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::query()->latest()->get();

        return view('customers.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'nic' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        Customer::create($data);

        return back()->with('success', 'Customer registered successfully.');
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'nic' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $customer->update($data);

        return back()->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return back()->with('success', 'Customer deleted successfully.');
    }
}
