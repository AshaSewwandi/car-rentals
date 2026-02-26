@extends('layouts.app')
@section('title', 'Support Requests')

@section('content')
<div class="page-toolbar">
  <div class="mb-1 mb-md-0">
    <h4 class="mb-1">Customer Support Requests</h4>
    <div class="text-muted">Review contact messages submitted from the website landing page.</div>
  </div>
</div>

<div class="card list-card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span class="header-title">Support Request List</span>
    <span class="small text-muted">Total: {{ $supportRequests->total() }}</span>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0 align-middle">
        <thead>
          <tr>
            <th style="min-width: 140px;">Received</th>
            <th style="min-width: 160px;">Name</th>
            <th style="min-width: 140px;">Phone</th>
            <th style="min-width: 180px;">Email</th>
            <th style="min-width: 340px;">Message</th>
          </tr>
        </thead>
        <tbody>
          @forelse($supportRequests as $requestItem)
            <tr>
              <td>{{ $requestItem->created_at?->format('Y-m-d H:i') }}</td>
              <td>{{ $requestItem->name }}</td>
              <td>{{ $requestItem->phone ?: '-' }}</td>
              <td>
                @if($requestItem->email)
                  <a href="mailto:{{ $requestItem->email }}">{{ $requestItem->email }}</a>
                @else
                  -
                @endif
              </td>
              <td>{{ $requestItem->message }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center p-4 text-muted">No support requests yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  @if($supportRequests->hasPages())
    <div class="card-footer bg-white">
      {{ $supportRequests->links() }}
    </div>
  @endif
</div>
@endsection

