@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<h4 class="mb-4 fw-bold">Dashboard</h4>

<div class="row g-3 mb-4">
    <div class="col-md-4 col-lg-2">
        <div class="stat-card">
            <div class="value">{{ $stats['total_sessions'] }}</div>
            <div class="label">Total Sessions</div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card">
            <div class="value text-primary">{{ $stats['active_sessions'] }}</div>
            <div class="label">Active</div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card">
            <div class="value text-success">{{ $stats['completed'] }}</div>
            <div class="label">Completed</div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card">
            <div class="value">{{ $stats['total_photos'] }}</div>
            <div class="label">Photos</div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card">
            <div class="value text-success">₱{{ number_format($stats['total_revenue'], 2) }}</div>
            <div class="label">Revenue</div>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="stat-card">
            <div class="value text-warning">{{ $stats['pending_payments'] }}</div>
            <div class="label">Pending Pay</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center" style="background:#111; border-color:#2a2a2a;">
        <span class="fw-semibold">Recent Sessions</span>
        <a href="{{ route('admin.sessions') }}" class="btn btn-sm btn-outline-light">View All</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Session ID</th>
                    <th>Customer</th>
                    <th>Template</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Created</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentSessions as $session)
                <tr>
                    <td><code>{{ $session->session_id }}</code></td>
                    <td>{{ $session->customer_name ?? '—' }}</td>
                    <td>{{ $session->template ?? '—' }}</td>
                    <td><span class="badge badge-{{ $session->status }}">{{ ucfirst($session->status) }}</span></td>
                    <td>
                        @if($session->payment)
                            <span class="badge badge-{{ $session->payment->status }}">{{ ucfirst($session->payment->status) }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-muted small">{{ $session->created_at->diffForHumans() }}</td>
                    <td>
                        <a href="{{ route('admin.session.detail', $session->session_id) }}" class="btn btn-sm btn-outline-secondary">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No sessions yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
