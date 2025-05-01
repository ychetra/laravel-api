<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'EcommerceX',
                'type' => 'string',
            ],
            [
                'key' => 'site_title',
                'value' => 'EcommerceX - Your Premier Shopping Destination',
                'type' => 'string',
            ],
            [
                'key' => 'site_description',
                'value' => 'Find the best products at competitive prices. Shop now for exclusive deals!',
                'type' => 'string',
            ],
            [
                'key' => 'contact_email',
                'value' => 'support@ecommercex.com',
                'type' => 'string',
            ],
            [
                'key' => 'contact_phone',
                'value' => '+1-888-555-0123',
                'type' => 'string',
            ],
            [
                'key' => 'social_links',
                'value' => json_encode([
                    'facebook' => 'https://facebook.com/ecommercex',
                    'twitter' => 'https://twitter.com/ecommercex',
                    'instagram' => 'https://instagram.com/ecommercex',
                    'pinterest' => 'https://pinterest.com/ecommercex',
                    'youtube' => 'https://youtube.com/ecommercex'
                ]),
                'type' => 'json',
            ],
            [
                'key' => 'site_logo',
                'value' => 'https://via.placeholder.com/250x100?text=EcommerceX',
                'type' => 'string',
            ],
            [
                'key' => 'site_favicon',
                'value' => 'https://via.placeholder.com/32x32?text=E',
                'type' => 'string',
            ],
            [
                'key' => 'primary_color',
                'value' => '#4A90E2',
                'type' => 'string',
            ],
            [
                'key' => 'secondary_color',
                'value' => '#50E3C2',
                'type' => 'string',
            ],
            [
                'key' => 'footer_text',
                'value' => '© 2025 EcommerceX. All rights reserved.',
                'type' => 'string',
            ],
            [
                'key' => 'address',
                'value' => '123 Commerce Street, Shopping District, NY 10001',
                'type' => 'string',
            ],
            [
                'key' => 'business_hours',
                'value' => json_encode([
                    'monday' => '9:00 AM - 6:00 PM',
                    'tuesday' => '9:00 AM - 6:00 PM',
                    'wednesday' => '9:00 AM - 6:00 PM',
                    'thursday' => '9:00 AM - 6:00 PM',
                    'friday' => '9:00 AM - 6:00 PM',
                    'saturday' => '10:00 AM - 4:00 PM',
                    'sunday' => 'Closed'
                ]),
                'type' => 'json',
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('site_settings')->updateOrInsert(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
} 