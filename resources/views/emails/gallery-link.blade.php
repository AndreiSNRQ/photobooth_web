<x-mail::message>
# Hey {{ $name }}! 📸

Your photobooth session is ready to view.

Click the button below to see your photos and download them anytime.

<x-mail::button :url="$galleryUrl" color="success">
View My Gallery
</x-mail::button>

Or copy this link:
{{ $galleryUrl }}

@if($session->qr_code_url)
You can also scan the QR code you received at the booth.
@endif

Thanks for using our photobooth!

{{ config('app.name') }}
</x-mail::message>
