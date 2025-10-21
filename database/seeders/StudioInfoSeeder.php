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
        $localizedEntries = [
            'name' => [
                'ru' => 'Репетиционная студия в Батуми',
                'en' => 'Rehearsal studio in Batumi',
            ],
            'address' => [
                'ru' => 'ул. Агмашенебели, 13т, Батуми, Грузия',
                'en' => '13t Agmashenebeli St., Batumi, Georgia',
            ],
        ];

        $sharedEntries = [
            'phone' => '+995 596 173 001',
            'email' => 'kropka.batumi@gmail.com',
            'instagram_url' => 'https://www.instagram.com/kropka_batumi/',
            'facebook_url' => 'https://www.facebook.com/Kropka.Batumi',
            'telegram_channel_url' => 'https://t.me/kropka_batumi',
            'telegram_admin_url' => 'https://t.me/kropka_batumi_admin',
        ];

        foreach ($localizedEntries as $property => $translations) {
            foreach ($translations as $locale => $value) {
                $this->storeValue("{$property}_{$locale}", $value);
            }
        }

        foreach ($sharedEntries as $property => $value) {
            $this->storeValue($property, $value);
        }
    }

    private function storeValue(string $property, string $value): void
    {
        StudioInfo::query()->updateOrCreate(
            ['property' => $property],
            ['value' => $value]
        );
    }
}
