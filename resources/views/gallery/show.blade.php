@extends('layouts.app')

@section('title', '📸 Your Gallery — ' . $session->session_id)

@push('styles')
<style>
    body { background: linear-gradient(135deg, #0f0f0f 0%, #1a0a2e 100%); min-height: 100vh; }
    .gallery-hero { text-align: center; padding: 3rem 1rem 1rem; }
    .gallery-hero h1 { font-size: 2rem; font-weight: 800; color: #e040fb; }
    .gallery-hero p  { color: #aaa; }
    .photo-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1rem; padding: 1rem; }
    .photo-card { border-radius: 12px; overflow: hidden; position: relative; background: #1a1a1a; }
    .photo-card img { width: 100%; height: 260px; object-fit: cover; display: block; transition: transform .3s; }
    .photo-card:hover img { transform: scale(1.04); }
    .photo-card .overlay { position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,.6); padding: .5rem; display: flex; justify-content: flex-end; gap: .5rem; opacity: 0; transition: opacity .3s; }
    .photo-card:hover .overlay { opacity: 1; }
    .collage-section { text-align: center; padding: 2rem 1rem; }
    .collage-section img { max-width: 480px; width: 100%; border-radius: 16px; box-shadow: 0 0 40px rgba(224,64,251,.3); }
    .qr-section { text-align: center; padding: 1rem; }
    .qr-section img { width: 160px; border-radius: 8px; background: #fff; padding: 8px; }
    .share-bar { background: #1a1a1a; border-top: 1px solid #2a2a2a; padding: 1rem; text-align: center; }
</style>
@endpush

@section('content')
<div class="gallery-hero">
    <h1>📸 Your Photobooth Gallery</h1>
    <p>Session: <code>{{ $session->session_id }}</code></p>
    @if($session->customer_name)
        <p class="text-light">Hey <strong>{{ $session->customer_name }}</strong>, here are your photos!</p>
    @endif
</div>

{{-- Collage --}}
@if($collage)
<div class="collage-section">
    <h4 class="text-white mb-3">🖼️ Your Collage</h4>
    <img src="{{ $collage->url }}" alt="Photo Collage">
    <div class="mt-3">
        <a href="{{ $collage->url }}" download class="btn btn-outline-light btn-sm">
            <i class="bi bi-download"></i> Download Collage
        </a>
    </div>
</div>
@endif

{{-- Individual Shots --}}
@if($shots->count())
<div class="container-fluid">
    <h5 class="text-white text-center mb-3">📷 Individual Shots</h5>
    <div class="photo-grid">
        @foreach($shots as $photo)
        <div class="photo-card">
            <img src="{{ $photo->url }}" alt="Shot {{ $photo->shot_number }}" loading="lazy">
            <div class="overlay">
                <a href="{{ $photo->url }}" download class="btn btn-sm btn-light">
                    <i class="bi bi-download"></i>
                </a>
                <a href="{{ $photo->url }}" target="_blank" class="btn btn-sm btn-outline-light">
                    <i class="bi bi-arrows-fullscreen"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- QR Code --}}
@if($session->qr_code_url)
<div class="qr-section mt-4">
    <p class="text-muted small">Share this gallery</p>
    <img src="{{ $session->qr_code_url }}" alt="QR Code">
    <p class="text-muted small mt-2">{{ $session->gallery_url }}</p>
</div>
@endif

{{-- Share Bar --}}
<div class="share-bar mt-4">
    <button class="btn btn-outline-light btn-sm" onclick="navigator.clipboard.writeText('{{ $session->gallery_url }}').then(()=>alert('Link copied!'))">
        <i class="bi bi-link-45deg"></i> Copy Link
    </button>
</div>
@endsection
