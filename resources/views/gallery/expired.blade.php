@extends('layouts.app')
@section('title', 'Gallery Expired')
@section('content')
<div class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="text-center">
        <div style="font-size:4rem;">⏰</div>
        <h2 class="text-white mt-3">Gallery Expired</h2>
        <p class="text-muted">This gallery link has expired. Please contact the booth operator.</p>
        <p class="text-muted small">Session: <code>{{ $session->session_id }}</code></p>
    </div>
</div>
@endsection
