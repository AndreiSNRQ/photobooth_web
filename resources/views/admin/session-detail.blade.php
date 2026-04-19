@extends('layouts.admin')
@section('title', 'Session ' . $session->session_id)

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.sessions') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0">Session <code>{{ $session->session_id }}</code></h4>
    <span class="badge badge-{{ $session->status }}">{{ ucfirst($session->status) }}</span>
</div>

<div class="row g-3">
    {{-- Info --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header" style="background:#111; border-color:#2a2a2a;">Session Info</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted">Customer</dt>
                    <dd class="col-7">{{ $session->customer_name ?? '—' }}</dd>
                    <dt class="col-5 text-muted">Email</dt>
                    <dd class="col-7">{{ $session->customer_email ?? '—' }}</dd>
                    <dt class="col-5 text-muted">Template</dt>
                    <dd class="col-7">{{ $session->template ?? '—' }}</dd>
                    <dt class="col-5 text-muted">Total Shots</dt>
                    <dd class="col-7">{{ $session->total_shots }}</dd>
                    <dt class="col-5 text-muted">Email Sent</dt>
                    <dd class="col-7">{{ $session->email_sent ? '✅ Yes' : '❌ No' }}</dd>
                    <dt class="col-5 text-muted">Expires</dt>
                    <dd class="col-7">{{ $session->expires_at?->format('M d, Y H:i') ?? '—' }}</dd>
                    <dt class="col-5 text-muted">Created</dt>
                    <dd class="col-7">{{ $session->created_at->format('M d, Y H:i') }}</dd>
                </dl>
            </div>
            <div class="card-footer d-flex gap-2" style="background:#111; border-color:#2a2a2a;">
                <a href="{{ route('gallery.show', $session->session_id) }}" target="_blank" class="btn btn-sm btn-outline-info">
                    <i class="bi bi-eye"></i> Gallery
                </a>
                <form method="POST" action="{{ route('admin.session.delete', $session->session_id) }}"
                      onsubmit="return confirm('Delete this session?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Delete</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Payment --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header" style="background:#111; border-color:#2a2a2a;">Payment</div>
            <div class="card-body">
                @if($session->payment)
                <dl class="row mb-0">
                    <dt class="col-5 text-muted">Reference</dt>
                    <dd class="col-7"><code>{{ $session->payment->reference_number }}</code></dd>
                    <dt class="col-5 text-muted">Amount</dt>
                    <dd class="col-7">₱{{ number_format($session->payment->amount, 2) }}</dd>
                    <dt class="col-5 text-muted">Method</dt>
                    <dd class="col-7">{{ strtoupper($session->payment->method) }}</dd>
                    <dt class="col-5 text-muted">Type</dt>
                    <dd class="col-7">{{ ucwords(str_replace('_', ' ', $session->payment->payment_type)) }}</dd>
                    <dt class="col-5 text-muted">Status</dt>
                    <dd class="col-7"><span class="badge badge-{{ $session->payment->status }}">{{ ucfirst($session->payment->status) }}</span></dd>
                    <dt class="col-5 text-muted">Paid At</dt>
                    <dd class="col-7">{{ $session->payment->paid_at?->format('M d, Y H:i') ?? '—' }}</dd>
                </dl>
                @else
                <p class="text-muted">No payment record.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- QR --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header" style="background:#111; border-color:#2a2a2a;">QR Code</div>
            <div class="card-body text-center">
                @if($session->qr_code_url)
                    <img src="{{ $session->qr_code_url }}" alt="QR" style="width:160px; background:#fff; padding:8px; border-radius:8px;">
                    <p class="text-muted small mt-2">{{ $session->gallery_url }}</p>
                @else
                    <p class="text-muted">No QR generated.</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Photos --}}
<div class="card mt-3">
    <div class="card-header" style="background:#111; border-color:#2a2a2a;">
        Photos ({{ $session->photos->count() }})
    </div>
    <div class="card-body">
        @if($session->photos->count())
        <div class="row g-2">
            @foreach($session->photos->sortBy('shot_number') as $photo)
            <div class="col-6 col-md-3 col-lg-2">
                <div class="position-relative">
                    <img src="{{ $photo->url }}" class="img-fluid rounded" style="height:140px; width:100%; object-fit:cover;" alt="Shot {{ $photo->shot_number }}">
                    @if($photo->is_collage)
                        <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-1">Collage</span>
                    @else
                        <span class="badge bg-secondary position-absolute top-0 start-0 m-1">#{{ $photo->shot_number }}</span>
                    @endif
                    <a href="{{ $photo->url }}" download class="btn btn-sm btn-dark position-absolute bottom-0 end-0 m-1">
                        <i class="bi bi-download"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-muted">No photos uploaded yet.</p>
        @endif
    </div>
</div>
@endsection
