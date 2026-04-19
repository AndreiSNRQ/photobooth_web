@extends('layouts.admin')
@section('title', 'Payments')

@section('content')
<h4 class="fw-bold mb-4">Payments</h4>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Session</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Paid At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td><code>{{ $payment->reference_number }}</code></td>
                    <td>
                        <a href="{{ route('admin.session.detail', $payment->session->session_id) }}" class="text-info">
                            {{ $payment->session->session_id }}
                        </a>
                    </td>
                    <td>{{ $payment->session->customer_name ?? '—' }}</td>
                    <td>₱{{ number_format($payment->amount, 2) }}</td>
                    <td>{{ strtoupper($payment->method) }}</td>
                    <td>{{ ucwords(str_replace('_', ' ', $payment->payment_type)) }}</td>
                    <td><span class="badge badge-{{ $payment->status }}">{{ ucfirst($payment->status) }}</span></td>
                    <td class="text-muted small">{{ $payment->paid_at?->format('M d, Y H:i') ?? '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No payments yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
    <div class="card-footer" style="background:#111; border-color:#2a2a2a;">
        {{ $payments->links() }}
    </div>
    @endif
</div>
@endsection
