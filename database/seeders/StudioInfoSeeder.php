<?php

namespace Database\Seeders;

use App\Models\StudioInfo;
use Illuminate\Database\Seeder;

class StudioInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entries = [
            'name' => 'Репетиционная студия в Батуми',
            'phone' => '+995 596 173 001',
            'address' => '13t Agmashenebeli st., Batumi, Georgia',
            'email' => 'kropka.batumi@gmail.com',
            'instagram_url' => 'https://www.instagram.com/kropka_batumi/',
            'facebook_url' => 'https://www.facebook.com/Kropka.Batumi',
            'telegram_channel_url' => 'https://t.me/kropka_batumi',
            'telegram_admin_url' => 'https://t.me/kropka_batumi_admin',
        ];

        foreach ($entries as $property => $value) {
            StudioInfo::query()->updateOrCreate(
                ['property' => $property],
                ['value' => $value]
            );
        }
    }
}
