<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Photo;
use App\Models\PhotoSession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $templates = ['classic-4', 'strip-3', 'square-2', 'wide-1'];
        $statuses  = ['active', 'completed', 'completed', 'completed', 'expired'];
        $methods   = ['cash', 'gcash', 'paypal'];

        for ($i = 1; $i <= 10; $i++) {
            $sessionId = strtoupper(Str::random(12));
            $status    = $statuses[array_rand($statuses)];

            $session = PhotoSession::create([
                'session_id'     => $sessionId,
                'template'       => $templates[array_rand($templates)],
                'total_shots'    => 4,
                'status'         => $status,
                'customer_name'  => fake()->name(),
                'customer_email' => fake()->safeEmail(),
                'gallery_url'    => url("/gallery/{$sessionId}"),
                'email_sent'     => $status === 'completed',
                'expires_at'     => now()->addHours(24),
            ]);

            // Fake photos (placeholder URLs)
            for ($s = 1; $s <= 4; $s++) {
                Photo::create([
                    'photo_session_id' => $session->id,
                    'filename'         => "shot_{$s}.jpg",
                    'path'             => "sessions/{$sessionId}/shot_{$s}.jpg",
                    'url'              => "https://picsum.photos/seed/{$sessionId}{$s}/600/800",
                    'storage_driver'   => 'local',
                    'shot_number'      => $s,
                    'is_collage'       => false,
                ]);
            }

            // Collage
            Photo::create([
                'photo_session_id' => $session->id,
                'filename'         => 'collage.jpg',
                'path'             => "sessions/{$sessionId}/collage.jpg",
                'url'              => "https://picsum.photos/seed/{$sessionId}c/800/600",
                'storage_driver'   => 'local',
                'shot_number'      => 0,
                'is_collage'       => true,
            ]);

            // Payment
            Payment::create([
                'photo_session_id' => $session->id,
                'reference_number' => 'PAY-' . strtoupper(Str::random(10)),
                'amount'           => 100.00,
                'method'           => $methods[array_rand($methods)],
                'payment_type'     => 'pay_now',
                'status'           => $status === 'completed' ? 'paid' : 'pending',
                'paid_at'          => $status === 'completed' ? now() : null,
            ]);
        }
    }
}
