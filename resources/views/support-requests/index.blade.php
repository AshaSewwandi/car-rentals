@extends('layouts.app')
@section('title', 'Support Requests')

@section('content')
<style>
  .support-table-wrap {
    overflow-x: auto;
  }

  @media (max-width: 920px) {
    .support-table-wrap {
      overflow: visible;
    }

    .support-table,
    .support-table thead,
    .support-table tbody,
    .support-table th,
    .support-table td,
    .support-table tr {
      display: block;
      width: 100%;
    }

    .support-table thead {
      display: none;
    }

    .support-table tbody tr {
      border: 1px solid #dbe6f3;
      border-radius: 12px;
      margin: .7rem;
      background: #fff;
      overflow: hidden;
      box-sizing: border-box;
      width: calc(100% - 1.4rem);
    }

    .support-table tbody td {
      position: relative;
      border-top: 1px solid #edf3fb;
      padding: .62rem .7rem .62rem 38%;
      min-height: 42px;
      word-break: break-word;
      overflow-wrap: anywhere;
      box-sizing: border-box;
      text-align: left !important;
    }

    .support-table tbody td:first-child {
      border-top: 0;
    }

    .support-table tbody td::before {
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

    .support-table tbody td.no-data {
      padding: 1rem .8rem !important;
      text-align: center !important;
      border-top: 0;
    }

    .support-table tbody td.no-data::before {
      display: none;
    }
  }
</style>
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
    <div class="table-responsive support-table-wrap">
      <table class="table table-striped mb-0 align-middle support-table">
        <thead>
          <tr>
            <th style="min-width: 140px;">Received</th>
            <th style="min-width: 160px;">Name</th>
            <th style="min-width: 140px;">Phone</th>
            <th style="min-width: 180px;">Email</th>
            <th style="min-width: 180px;">Source</th>
            <th style="min-width: 340px;">Message</th>
          </tr>
        </thead>
        <tbody>
          @forelse($supportRequests as $requestItem)
            <tr>
              <td data-label="Received">{{ $requestItem->created_at?->format('Y-m-d H:i') }}</td>
              <td data-label="Name">{{ $requestItem->name }}</td>
              <td data-label="Phone">{{ $requestItem->phone ?: '-' }}</td>
              <td data-label="Email">
                @if($requestItem->email)
                  <a href="mailto:{{ $requestItem->email }}">{{ $requestItem->email }}</a>
                @else
                  -
                @endif
              </td>
              <td data-label="Source">
                <span class="small text-muted">{{ $requestItem->source_page ?: '-' }}</span>
              </td>
              <td data-label="Message">{{ $requestItem->message }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center p-4 text-muted no-data">No support requests yet.</td>
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
