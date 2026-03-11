@extends('layouts.app')
@section('title', 'User Management')

@section('content')
<style>
  .users-table-wrap {
    overflow-x: auto;
  }

  @media (max-width: 920px) {
    .page-toolbar {
      display: grid;
      gap: .7rem;
    }

    .page-toolbar .btn {
      width: 100%;
    }

    .users-table-wrap {
      overflow: visible;
    }

    .users-table,
    .users-table thead,
    .users-table tbody,
    .users-table th,
    .users-table td,
    .users-table tr {
      display: block;
      width: 100%;
    }

    .users-table thead {
      display: none;
    }

    .users-table tbody tr {
      border: 1px solid #dbe6f3;
      border-radius: 12px;
      margin: .7rem;
      background: #fff;
      overflow: hidden;
      box-sizing: border-box;
      width: calc(100% - 1.4rem);
    }

    .users-table tbody td {
      position: relative;
      border-top: 1px solid #edf3fb;
      padding: .62rem .7rem .62rem 38%;
      min-height: 42px;
      word-break: break-word;
      overflow-wrap: anywhere;
      box-sizing: border-box;
      text-align: left !important;
    }

    .users-table tbody td:first-child {
      border-top: 0;
    }

    .users-table tbody td::before {
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

    .users-table tbody td.user-actions {
      padding-left: .7rem;
      display: flex;
      flex-direction: column;
      gap: .42rem;
      align-items: stretch;
    }

    .users-table tbody td.user-actions::before {
      position: static;
      display: block;
      width: auto;
      margin-bottom: .4rem;
    }

    .users-table tbody td.user-actions .btn {
      width: 100%;
      margin: 0 !important;
    }

    .users-table tbody td.no-data {
      padding: 1rem .8rem !important;
      text-align: center !important;
      border-top: 0;
    }

    .users-table tbody td.no-data::before {
      display: none;
    }
  }
</style>
<div class="page-toolbar">
  <div class="mb-1 mb-md-0">
    <h4 class="mb-1">User Management</h4>
    <div class="text-muted">Create team accounts and control access by assigning user roles.</div>
  </div>
  <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>
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

<div class="card list-card">
  <div class="card-header"><span class="header-title">Users</span></div>
  <div class="card-body p-0">
    <div class="table-responsive users-table-wrap">
      <table class="table table-striped mb-0 align-middle users-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Revenue Split</th>
            <th>Created</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $user)
            <tr>
              <td data-label="Name">{{ $user->name }}</td>
              <td data-label="Email">{{ $user->email }}</td>
              <td data-label="Phone">{{ $user->phone ?: '-' }}</td>
              <td data-label="Role">
                <span class="badge {{ $user->role === 'admin' ? 'bg-success' : ($user->role === 'partner' ? 'bg-primary' : 'bg-secondary') }}">
                  {{ ucfirst($user->role) }}
                </span>
              </td>
              <td data-label="Revenue Split">
                @if($user->role === 'partner')
                  <div class="small fw-semibold">Partner {{ number_format((float) $user->partner_share_percentage, 2) }}%</div>
                  <div class="small text-muted">Admin {{ number_format((float) $user->admin_share_percentage, 2) }}%</div>
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
              <td data-label="Created">{{ $user->created_at?->format('Y-m-d') }}</td>
              <td data-label="Action" class="text-nowrap user-actions">
                <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">Edit</button>
                @if(auth()->id() !== $user->id)
                  <button
                    type="button"
                    class="btn btn-sm btn-outline-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteUserModal"
                    data-delete-url="{{ route('users.destroy', $user) }}"
                    data-user-name="{{ $user->name }}"
                  >
                    Delete
                  </button>
                @endif
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center p-4 text-muted no-data">No users found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="post" action="{{ route('users.store') }}">
        @csrf
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
          </div>
          <div class="mb-2">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
              <option value="customer" @selected(old('role') === 'customer')>Customer</option>
              <option value="partner" @selected(old('role') === 'partner')>Partner</option>
              <option value="admin" @selected(old('role') === 'admin')>Admin</option>
            </select>
          </div>
          <div class="row g-2 mb-2">
            <div class="col-md-6">
              <label class="form-label">Partner Percentage</label>
              <input type="number" step="0.01" min="0" max="100" name="partner_share_percentage" class="form-control" value="{{ old('partner_share_percentage', 80) }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">Admin Percentage</label>
              <input type="number" step="0.01" min="0" max="100" name="admin_share_percentage" class="form-control" value="{{ old('admin_share_percentage', 20) }}">
            </div>
            <div class="col-12">
              <small class="text-muted">For partner accounts, partner percentage plus admin percentage must equal 100.</small>
            </div>
          </div>
          <div class="mb-2">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="mb-1">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-dark">Create User</button>
        </div>
      </form>
    </div>
  </div>
</div>

@foreach($users as $user)
  <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post" action="{{ route('users.update', $user) }}">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="mb-2">
              <label class="form-label">Name</label>
              <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
            </div>
            <div class="mb-2">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
            </div>
            <div class="mb-2">
              <label class="form-label">Phone</label>
              <input type="text" name="phone" class="form-control" value="{{ $user->phone }}" required>
            </div>
            <div class="mb-2">
              <label class="form-label">Role</label>
              <select name="role" class="form-select" required>
                <option value="customer" @selected($user->role === 'customer')>Customer</option>
                <option value="partner" @selected($user->role === 'partner')>Partner</option>
                <option value="admin" @selected($user->role === 'admin')>Admin</option>
              </select>
              @if(auth()->id() === $user->id)
                <small class="text-muted">Your own role cannot be changed from admin to customer.</small>
              @endif
            </div>
            <div class="row g-2 mb-2">
              <div class="col-md-6">
                <label class="form-label">Partner Percentage</label>
                <input type="number" step="0.01" min="0" max="100" name="partner_share_percentage" class="form-control" value="{{ old('partner_share_percentage', $user->partner_share_percentage ?? 80) }}">
              </div>
              <div class="col-md-6">
                <label class="form-label">Admin Percentage</label>
                <input type="number" step="0.01" min="0" max="100" name="admin_share_percentage" class="form-control" value="{{ old('admin_share_percentage', $user->admin_share_percentage ?? 20) }}">
              </div>
              <div class="col-12">
                <small class="text-muted">For partner accounts, partner percentage plus admin percentage must equal 100.</small>
              </div>
            </div>
            <div class="mb-2">
              <label class="form-label">New Password (optional)</label>
              <input type="password" name="password" class="form-control">
            </div>
            <div class="mb-1">
              <label class="form-label">Confirm New Password</label>
              <input type="password" name="password_confirmation" class="form-control">
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

<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteUserModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0" id="deleteUserText">Are you sure you want to delete this user?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
        <form method="post" id="deleteUserForm" class="d-inline">
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
    const deleteModal = document.getElementById('deleteUserModal');
    const deleteForm = document.getElementById('deleteUserForm');
    const deleteText = document.getElementById('deleteUserText');

    if (!deleteModal || !deleteForm || !deleteText) {
      return;
    }

    deleteModal.addEventListener('show.bs.modal', function (event) {
      const trigger = event.relatedTarget;
      const deleteUrl = trigger?.getAttribute('data-delete-url');
      const userName = trigger?.getAttribute('data-user-name') || 'this user';

      if (deleteUrl) {
        deleteForm.setAttribute('action', deleteUrl);
      }

      deleteText.textContent = `Are you sure you want to delete "${userName}"?`;
    });
  });
</script>
@endsection
