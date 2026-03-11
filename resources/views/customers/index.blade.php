@extends('layouts.app')
@section('title', 'Customer Registration')

@section('content')
<style>
  .customer-table-wrap {
    overflow-x: auto;
  }

  @media (max-width: 920px) {
    .customer-table-wrap {
      overflow: visible;
    }

    .customer-table,
    .customer-table thead,
    .customer-table tbody,
    .customer-table th,
    .customer-table td,
    .customer-table tr {
      display: block;
      width: 100%;
    }

    .customer-table thead {
      display: none;
    }

    .customer-table tbody tr {
      border: 1px solid #dbe6f3;
      border-radius: 12px;
      margin: .7rem;
      background: #fff;
      overflow: hidden;
      box-sizing: border-box;
      width: calc(100% - 1.4rem);
    }

    .customer-table tbody td {
      position: relative;
      border-top: 1px solid #edf3fb;
      padding: .62rem .7rem .62rem 38%;
      min-height: 42px;
      word-break: break-word;
      overflow-wrap: anywhere;
      box-sizing: border-box;
    }

    .customer-table tbody td:first-child {
      border-top: 0;
    }

    .customer-table tbody td::before {
      content: attr(data-label);
      position: absolute;
      left: .7rem;
      top: .62rem;
      width: calc(38% - 1rem);
      color: #64748b;
      font-size: .72rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: .04em;
      line-height: 1.2;
    }

    .customer-table tbody td.customer-actions {
      padding-left: .7rem;
      display: flex;
      flex-direction: column;
      gap: .42rem;
      align-items: stretch;
    }

    .customer-table tbody td.customer-actions::before {
      position: static;
      display: block;
      width: auto;
      margin-bottom: .4rem;
    }

    .customer-table tbody td.customer-actions .btn {
      width: 100%;
      margin: 0 !important;
    }

    .customer-table tbody td.no-data {
      padding: 1rem .8rem !important;
      text-align: center;
      border-top: 0;
    }

    .customer-table tbody td.no-data::before {
      display: none;
    }
  }
</style>
<div class="page-toolbar">
  <div class="mb-3">
    <h4 class="mb-1">Customers</h4>
    <div class="text-muted">Store customer contact details and keep records ready for rentals and agreements.</div>
  </div>
</div>

<div class="card list-card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span class="header-title">Customer List</span>
    <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#addCustomerModal">Add Customer</button>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive customer-table-wrap">
      <table class="table table-striped mb-0 customer-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Phone</th>
            <th>NIC</th>
            <th>Address</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($customers as $customer)
            <tr>
              <td data-label="Name">{{ $customer->name }}</td>
              <td data-label="Phone">{{ $customer->phone ?: '-' }}</td>
              <td data-label="NIC">{{ $customer->nic ?: '-' }}</td>
              <td data-label="Address">{{ $customer->address ?: '-' }}</td>
              <td data-label="Action" class="text-nowrap customer-actions">
                <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#editCustomerModal{{ $customer->id }}">Update</button>
                <button
                  type="button"
                  class="btn btn-sm btn-outline-danger"
                  data-bs-toggle="modal"
                  data-bs-target="#deleteCustomerModal"
                  data-delete-url="{{ route('customers.destroy', $customer) }}"
                  data-customer-name="{{ $customer->name }}"
                >
                  Delete
                </button>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center p-4 text-muted no-data">No customers yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

@foreach($customers as $customer)
  <div class="modal fade" id="editCustomerModal{{ $customer->id }}" tabindex="-1" aria-labelledby="editCustomerModalLabel{{ $customer->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editCustomerModalLabel{{ $customer->id }}">Edit Customer</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post" action="{{ route('customers.update', $customer) }}">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="mb-2">
              <label class="form-label">Name</label>
              <input type="text" name="name" class="form-control" value="{{ $customer->name }}" required>
            </div>
            <div class="mb-2">
              <label class="form-label">Phone</label>
              <input type="text" name="phone" class="form-control" value="{{ $customer->phone }}">
            </div>
            <div class="mb-2">
              <label class="form-label">NIC</label>
              <input type="text" name="nic" class="form-control" value="{{ $customer->nic }}">
            </div>
            <div class="mb-1">
              <label class="form-label">Address</label>
              <input type="text" name="address" class="form-control" value="{{ $customer->address }}">
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

<div class="modal fade" id="deleteCustomerModal" tabindex="-1" aria-labelledby="deleteCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteCustomerModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0" id="deleteCustomerText">Are you sure you want to delete this customer?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
        <form method="post" id="deleteCustomerForm" class="d-inline">
          @csrf
          @method('DELETE')
          <button class="btn btn-outline-danger">Yes, Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addCustomerModalLabel">Register Customer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" action="{{ route('customers.store') }}">
        @csrf
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
          </div>
          <div class="mb-2">
            <label class="form-label">NIC</label>
            <input type="text" name="nic" class="form-control @error('nic') is-invalid @enderror" value="{{ old('nic') }}">
          </div>
          <div class="mb-1">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-dark">Save Customer</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const deleteModal = document.getElementById('deleteCustomerModal');
    const deleteForm = document.getElementById('deleteCustomerForm');
    const deleteText = document.getElementById('deleteCustomerText');

    if (!deleteModal || !deleteForm || !deleteText) {
      return;
    }

    deleteModal.addEventListener('show.bs.modal', function (event) {
      const trigger = event.relatedTarget;
      const deleteUrl = trigger?.getAttribute('data-delete-url');
      const customerName = trigger?.getAttribute('data-customer-name') || 'this customer';

      if (deleteUrl) {
        deleteForm.setAttribute('action', deleteUrl);
      }

      deleteText.textContent = `Are you sure you want to delete "${customerName}"?`;
    });
  });
</script>
@endsection
