@extends('layouts.app')
@section('title', 'Expenses Management')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
  <div class="mb-1 mb-md-0">
    <h4 class="mb-1">Manage Expenses</h4>
    <div class="text-muted">Track service and operational costs by car, month, and expense type.</div>
  </div>
  <form class="d-flex gap-2" method="get" action="{{ route('expenses.index') }}">
    <input type="month" class="form-control" name="month" value="{{ $month }}">
    <button class="btn btn-dark">Filter</button>
  </form>
</div>

@if($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span>Expense List ({{ $month }})</span>
    <div class="d-flex align-items-center gap-2">
      <strong>Rs {{ number_format($total, 2) }}</strong>
      <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#addExpenseModal">Add Expense</button>
    </div>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th>Date</th>
            <th>Car</th>
            <th>Type</th>
            <th class="text-end">Amount</th>
            <th>Note</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($expenses as $expense)
            <tr>
              <td>{{ $expense->date->format('Y-m-d') }}</td>
              <td>{{ $expense->car?->name }}{{ $expense->car?->plate_no ? ' (' . $expense->car->plate_no . ')' : '' }}</td>
              <td>{{ ucfirst($expense->type) }}</td>
              <td class="text-end">Rs {{ number_format($expense->amount, 2) }}</td>
              <td>{{ $expense->note ?: '-' }}</td>
              <td class="text-nowrap">
                <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#editExpenseModal{{ $expense->id }}">Update</button>
                <button
                  type="button"
                  class="btn btn-sm btn-outline-danger"
                  data-bs-toggle="modal"
                  data-bs-target="#deleteExpenseModal"
                  data-delete-url="{{ route('expenses.destroy', $expense) }}"
                  data-expense-text="{{ $expense->date->format('Y-m-d') }} | {{ $expense->car?->name }} | Rs {{ number_format($expense->amount, 2) }}"
                >
                  Delete
                </button>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center p-4 text-muted">No expenses found for {{ $month }}.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addExpenseModalLabel">Add Expense</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" action="{{ route('expenses.store') }}">
        @csrf
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">Car</label>
            <select name="car_id" class="form-select @error('car_id') is-invalid @enderror" required>
              <option value="">Select Car</option>
              @foreach($cars as $car)
                <option value="{{ $car->id }}" @selected(old('car_id') == $car->id)>{{ $car->name }} ({{ $car->plate_no }})</option>
              @endforeach
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', now()->format('Y-m-d')) }}" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Type</label>
            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
              @foreach(['service','repair','insurance','license','tyre','other'] as $type)
                <option value="{{ $type }}" @selected(old('type') === $type)>{{ ucfirst($type) }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label">Amount</label>
            <input type="number" step="0.01" min="0" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
          </div>
          <div class="mb-1">
            <label class="form-label">Note</label>
            <input type="text" name="note" class="form-control @error('note') is-invalid @enderror" value="{{ old('note') }}">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-dark">Save Expense</button>
        </div>
      </form>
    </div>
  </div>
</div>

@foreach($expenses as $expense)
  <div class="modal fade" id="editExpenseModal{{ $expense->id }}" tabindex="-1" aria-labelledby="editExpenseModalLabel{{ $expense->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editExpenseModalLabel{{ $expense->id }}">Edit Expense</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post" action="{{ route('expenses.update', $expense) }}">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="mb-2">
              <label class="form-label">Car</label>
              <select name="car_id" class="form-select" required>
                @foreach($cars as $car)
                  <option value="{{ $car->id }}" @selected($expense->car_id === $car->id)>{{ $car->name }} ({{ $car->plate_no }})</option>
                @endforeach
              </select>
            </div>
            <div class="mb-2">
              <label class="form-label">Date</label>
              <input type="date" name="date" class="form-control" value="{{ $expense->date->format('Y-m-d') }}" required>
            </div>
            <div class="mb-2">
              <label class="form-label">Type</label>
              <select name="type" class="form-select" required>
                @foreach(['service','repair','insurance','license','tyre','other'] as $type)
                  <option value="{{ $type }}" @selected($expense->type === $type)>{{ ucfirst($type) }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-2">
              <label class="form-label">Amount</label>
              <input type="number" step="0.01" min="0" name="amount" class="form-control" value="{{ $expense->amount }}" required>
            </div>
            <div class="mb-1">
              <label class="form-label">Note</label>
              <input type="text" name="note" class="form-control" value="{{ $expense->note }}">
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
@endforeach

<div class="modal fade" id="deleteExpenseModal" tabindex="-1" aria-labelledby="deleteExpenseModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteExpenseModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0" id="deleteExpenseText">Are you sure you want to delete this expense?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
        <form method="post" id="deleteExpenseForm" class="d-inline">
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
    const deleteModal = document.getElementById('deleteExpenseModal');
    const deleteForm = document.getElementById('deleteExpenseForm');
    const deleteText = document.getElementById('deleteExpenseText');

    if (!deleteModal || !deleteForm || !deleteText) {
      return;
    }

    deleteModal.addEventListener('show.bs.modal', function (event) {
      const trigger = event.relatedTarget;
      const deleteUrl = trigger?.getAttribute('data-delete-url');
      const expenseText = trigger?.getAttribute('data-expense-text') || 'this expense';

      if (deleteUrl) {
        deleteForm.setAttribute('action', deleteUrl);
      }

      deleteText.textContent = `Are you sure you want to delete "${expenseText}"?`;
    });
  });
</script>
@endsection
