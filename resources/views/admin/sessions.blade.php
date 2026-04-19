@extends('layouts.admin')
@section('title', 'Sessions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Sessions</h4>
</div>

{{-- Filters --}}
<form method="GET" class="row g-2 mb-4">
    <div class="col-md-4">
        <input type="text" name="search" class="form-control form-control-sm bg-dark text-light border-secondary"
               placeholder="Search session ID, name, email..." value="{{ request('search') }}">
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select form-select-sm bg-dark text-light border-secondary">
            <option value="">All Statuses</option>
            @foreach(['active','completed','pending','expired'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button class="btn btn-sm btn-outline-light" type="submit">Filter</button>
        <a href="{{ route('admin.sessions') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
    </div>
</form>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Session ID</th>
                    <th>Customer</th>
                    <th>Template</th>
                    <th>Photos</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $session)
                <tr>
                    <td><code>{{ $session->session_id }}</code></td>
                    <td>
                        <div>{{ $session->customer_name ?? '—' }}</div>
                        <small class="text-muted">{{ $session->customer_email ?? '' }}</small>
                    </td>
                    <td>{{ $session->template ?? '—' }}</td>
                    <td>{{ $session->photos->count() }}</td>
                    <td><span class="badge badge-{{ $session->status }}">{{ ucfirst($session->status) }}</span></td>
                    <td>
                        @if($session->payment)
                            <span class="badge badge-{{ $session->payment->status }}">{{ ucfirst($session->payment->status) }}</span>
                            <div class="small text-muted">₱{{ number_format($session->payment->amount, 2) }}</div>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-muted small">{{ $session->created_at->format('M d, Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.session.detail', $session->session_id) }}" class="btn btn-sm btn-outline-secondary">View</a>
                        <a href="{{ route('gallery.show', $session->session_id) }}" target="_blank" class="btn btn-sm btn-outline-info">Gallery</a>
                        <form method="POST" action="{{ route('admin.session.delete', $session->session_id) }}" class="d-inline"
                              onsubmit="return confirm('Delete this session?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Del</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No sessions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($sessions->hasPages())
    <div class="card-footer" style="background:#111; border-color:#2a2a2a;">
        {{ $sessions->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
