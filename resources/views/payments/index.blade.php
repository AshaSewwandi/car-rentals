@extends('layouts.app')
@section('title', 'Payments')

@section('content')
<div class="page-toolbar">
  <div class="mb-3">
    <h4 class="mb-1">Manage Payments</h4>
    <div class="text-muted">Track monthly rental payments, due dates, and payment status.</div>
  </div>
  <form class="d-flex gap-2" method="get" action="{{ route('payments.index') }}">
    <input type="month" class="form-control" name="month" value="{{ $month }}">
    <button class="btn btn-dark">Filter</button>
  </form>
</div>

@if(auth()->user()->isAdmin())
  <div class="card list-card mb-3">
    <div class="card-header">
      <span class="header-title">Online Transfer Payment Details</span>
    </div>
    <div class="card-body">
      <form method="post" action="{{ route('payments.bank-details.update') }}">
        @csrf
        <div class="row g-2">
          <div class="col-12 col-md-6">
            <label class="form-label">Account Number</label>
            <input
              type="text"
              name="account_number"
              class="form-control"
              value="{{ old('account_number', $paymentDetails['account_number']) }}"
              required
            >
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label">Account Name</label>
            <input
              type="text"
              name="account_name"
              class="form-control"
              value="{{ old('account_name', $paymentDetails['account_name']) }}"
              required
            >
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label">Bank</label>
            <input
              type="text"
              name="bank_name"
              class="form-control"
              value="{{ old('bank_name', $paymentDetails['bank_name']) }}"
              required
            >
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label">Branch</label>
            <input
              type="text"
              name="branch_name"
              class="form-control"
              value="{{ old('branch_name', $paymentDetails['branch_name']) }}"
              required
            >
          </div>
          <div class="col-12">
            <label class="form-label">Customer Help Text</label>
            <input
              type="text"
              name="help_text"
              class="form-control"
              value="{{ old('help_text', $paymentDetails['help_text']) }}"
              required
            >
          </div>
        </div>
        <div class="mt-3 d-flex justify-content-end">
          <button class="btn btn-dark">Save Payment Details</button>
        </div>
      </form>
    </div>
  </div>
@endif

@if($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="card list-card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span class="header-title">Payment List</span>
    <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#addPaymentModal">Add Payment</button>
  </div>
  <div class="card-body p-0">
    @if($rentals->isEmpty())
      <div class="alert alert-warning m-3 mb-0">No rentals found. Create a rental first.</div>
    @endif
    <div class="table-responsive">
      <table class="table table-striped mb-0 align-middle">
        <thead>
          <tr>
            <th>Car</th>
            <th>Customer</th>
            <th>Month</th>
            <th>Due Date</th>
            <th class="text-end">Amount</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($payments as $payment)
            <tr>
              <td>{{ $payment->rental->car->name }}</td>
              <td>{{ $payment->rental->customer->name }}</td>
              <td>{{ $payment->month }}</td>
              <td>{{ $payment->due_date->format('Y-m-d') }}</td>
              <td class="text-end">Rs {{ number_format($payment->amount, 2) }}</td>
              <td>
                @if($payment->status === 'paid')
                  <span class="badge bg-success">Paid</span>
                @elseif($payment->is_late)
                  <span class="badge bg-danger">Late</span>
                @else
                  <span class="badge bg-warning text-dark">Pending</span>
                @endif
              </td>
              <td class="text-nowrap">
                @if($payment->status === 'pending')
                  <button class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#markPaidModal{{ $payment->id }}">Mark Paid</button>
                @endif
                <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#editPaymentModal{{ $payment->id }}">Update</button>
                <button
                  type="button"
                  class="btn btn-sm btn-outline-danger"
                  data-bs-toggle="modal"
                  data-bs-target="#deletePaymentModal"
                  data-delete-url="{{ route('payments.destroy', $payment) }}"
                  data-payment-text="{{ $payment->rental->car->name }} | {{ $payment->rental->customer->name }} | {{ $payment->month }}"
                >
                  Delete
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center p-4 text-muted">No payments found for {{ $month }}.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addPaymentModalLabel">Add Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" action="{{ route('payments.store') }}">
        @csrf
        <div class="modal-body">
          <div class="row g-2">
            <div class="col-12 col-lg-4">
              <label class="form-label">Rental</label>
              <select name="rental_id" class="form-select" required>
                <option value="">Select Rental</option>
                @foreach($rentals as $rental)
                  <option value="{{ $rental->id }}" @selected(old('rental_id') == $rental->id)>
                    {{ $rental->car->plate_no }} - {{ $rental->car->name }} - {{ $rental->customer->name }}
                    ({{ strtoupper($rental->status) }}, Rs {{ number_format($rental->monthly_rent, 2) }})
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-6 col-lg-2">
              <label class="form-label">Month</label>
              <input type="month" name="month" class="form-control" value="{{ old('month', $month) }}" required>
            </div>
            <div class="col-6 col-lg-2">
              <label class="form-label">Due Date</label>
              <input type="date" name="due_date" class="form-control" value="{{ old('due_date', now()->format('Y-m-d')) }}" required>
            </div>
            <div class="col-6 col-lg-2">
              <label class="form-label">Amount</label>
              <input type="number" step="0.01" min="0" name="amount" class="form-control" value="{{ old('amount') }}" required>
            </div>
            <div class="col-6 col-lg-2">
              <label class="form-label">Status</label>
              <select name="status" class="form-select" required>
                <option value="pending" @selected(old('status') === 'pending')>Pending</option>
                <option value="paid" @selected(old('status') === 'paid')>Paid</option>
              </select>
            </div>
            <div class="col-6 col-lg-3">
              <label class="form-label">Paid Date (optional)</label>
              <input type="date" name="paid_date" class="form-control" value="{{ old('paid_date') }}">
            </div>
            <div class="col-6 col-lg-3">
              <label class="form-label">Paid Amount (optional)</label>
              <input type="number" step="0.01" min="0" name="paid_amount" class="form-control" value="{{ old('paid_amount') }}">
            </div>
            <div class="col-6 col-lg-3">
              <label class="form-label">Method (optional)</label>
              <select name="method" class="form-select">
                <option value="">Select method</option>
                <option value="cash" @selected(old('method') === 'cash')>Cash</option>
                <option value="bank" @selected(old('method') === 'bank')>Bank</option>
                <option value="online" @selected(old('method') === 'online')>Online</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-dark" @disabled($rentals->isEmpty())>Add Payment</button>
        </div>
      </form>
    </div>
  </div>
</div>

@foreach($payments as $payment)
  <div class="modal fade" id="editPaymentModal{{ $payment->id }}" tabindex="-1" aria-labelledby="editPaymentModalLabel{{ $payment->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editPaymentModalLabel{{ $payment->id }}">Edit Payment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post" action="{{ route('payments.update', $payment) }}">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="row g-2">
              <div class="col-12 col-md-6">
                <label class="form-label">Month</label>
                <input type="month" name="month" class="form-control" value="{{ $payment->month }}" required>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Due Date</label>
                <input type="date" name="due_date" class="form-control" value="{{ $payment->due_date->format('Y-m-d') }}" required>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Amount</label>
                <input type="number" step="0.01" min="0" name="amount" class="form-control" value="{{ $payment->amount }}" required>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                  <option value="pending" @selected($payment->status === 'pending')>Pending</option>
                  <option value="paid" @selected($payment->status === 'paid')>Paid</option>
                </select>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Paid Date</label>
                <input type="date" name="paid_date" class="form-control" value="{{ $payment->paid_date?->format('Y-m-d') }}">
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label">Paid Amount</label>
                <input type="number" step="0.01" min="0" name="paid_amount" class="form-control" value="{{ $payment->paid_amount }}">
              </div>
              <div class="col-12">
                <label class="form-label">Method</label>
                <select name="method" class="form-select">
                  <option value="">Select method</option>
                  <option value="cash" @selected($payment->method === 'cash')>Cash</option>
                  <option value="bank" @selected($payment->method === 'bank')>Bank</option>
                  <option value="online" @selected($payment->method === 'online')>Online</option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
            <button class="btn btn-dark">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @if($payment->status === 'pending')
    <div class="modal fade" id="markPaidModal{{ $payment->id }}" tabindex="-1" aria-labelledby="markPaidModalLabel{{ $payment->id }}" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="markPaidModalLabel{{ $payment->id }}">Mark Payment As Paid</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="post" action="{{ route('payments.paid', $payment) }}">
            @csrf
            <div class="modal-body">
              <div class="mb-2">
                <label class="form-label">Paid Date</label>
                <input type="date" name="paid_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
              </div>
              <div class="mb-2">
                <label class="form-label">Paid Amount</label>
                <input type="number" step="0.01" min="0" name="paid_amount" class="form-control" value="{{ $payment->amount }}" required>
              </div>
              <div class="mb-1">
                <label class="form-label">Method</label>
                <select name="method" class="form-select" required>
                  <option value="cash">Cash</option>
                  <option value="bank">Bank</option>
                  <option value="online">Online</option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
              <button class="btn btn-dark">Confirm Paid</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
@endforeach

<div class="modal fade" id="deletePaymentModal" tabindex="-1" aria-labelledby="deletePaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deletePaymentModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0" id="deletePaymentText">Are you sure you want to delete this payment?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
        <form method="post" id="deletePaymentForm" class="d-inline">
          @csrf
          @method('DELETE')
          <button class="btn btn-outline-danger">Yes, Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const deleteModal = document.getElementById('deletePaymentModal');
    const deleteForm = document.getElementById('deletePaymentForm');
    const deleteText = document.getElementById('deletePaymentText');

    if (!deleteModal || !deleteForm || !deleteText) {
      return;
    }

    deleteModal.addEventListener('show.bs.modal', function (event) {
      const trigger = event.relatedTarget;
      const deleteUrl = trigger?.getAttribute('data-delete-url');
      const paymentText = trigger?.getAttribute('data-payment-text') || 'this payment';

      if (deleteUrl) {
        deleteForm.setAttribute('action', deleteUrl);
      }

      deleteText.textContent = `Are you sure you want to delete "${paymentText}"?`;
    });
  });
</script>
@endsection
