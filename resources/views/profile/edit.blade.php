@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<div class="page-toolbar">
  <div>
    <h4 class="mb-1">Edit Profile</h4>
    <div class="text-muted">Update your account details and password.</div>
  </div>
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
  <div class="card-header">
    <span class="header-title">Profile Details</span>
  </div>
  <div class="card-body">
    <form method="post" action="{{ route('profile.update') }}">
      @csrf
      @method('PUT')

      <div class="row g-3">
        <div class="col-12 col-lg-6">
          <label class="form-label">Name</label>
          <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="col-12 col-lg-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>
      </div>

      <hr class="my-4">
      <div class="mb-2 fw-semibold">Change Password (Optional)</div>
      <div class="text-muted small mb-3">Only fill these fields if you want to set a new password.</div>

      <div class="row g-3">
        <div class="col-12 col-lg-4">
          <label class="form-label">Current Password</label>
          <input type="password" name="current_password" class="form-control" autocomplete="current-password">
        </div>

        <div class="col-12 col-lg-4">
          <label class="form-label">New Password</label>
          <input type="password" name="password" class="form-control" autocomplete="new-password">
        </div>

        <div class="col-12 col-lg-4">
          <label class="form-label">Confirm New Password</label>
          <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
        </div>
      </div>

      <div class="mt-4">
        <button class="btn btn-dark">Save Profile</button>
      </div>
    </form>
  </div>
</div>
@endsection

