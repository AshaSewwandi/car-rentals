@extends('layouts.app')
@section('title', 'Permission Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="mb-1">Permission Management</h4>
    <div class="text-muted">Set module access for each role to control what users can view and manage.</div>
  </div>
</div>

<div class="row g-3">
  @foreach($roles as $role)
    <div class="col-12 col-xl-6">
      <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>{{ ucfirst($role) }} Permissions</span>
        </div>
        <form method="post" action="{{ route('permissions.update', $role) }}">
          @csrf
          @method('PUT')
          <div class="card-body">
            <div class="row g-2">
              @foreach($modules as $key => $label)
                <div class="col-12 col-md-6">
                  <label class="form-check d-flex align-items-center gap-2">
                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $key }}" @checked($matrix[$role][$key] ?? false)>
                    <span>{{ $label }}</span>
                  </label>
                </div>
              @endforeach
            </div>
          </div>
          <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-3">
            <button class="btn btn-dark w-100">Save {{ ucfirst($role) }} Permissions</button>
          </div>
        </form>
      </div>
    </div>
  @endforeach
</div>
@endsection
