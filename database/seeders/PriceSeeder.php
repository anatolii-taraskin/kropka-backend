<?php

namespace Database\Seeders;

use App\Models\Price;
use Illuminate\Database\Seeder;

class PriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prices = [
            [
                'name_ru' => 'Соло / с учеником',
                'name_en' => 'Solo / with student',
                'col1_ru' => '1 час — 20 ₾',
                'col1_en' => '1 hour — 20 ₾',
                'col2_ru' => '2 часа — 30 ₾',
                'col2_en' => '2 hours — 30 ₾',
                'col3_ru' => '3 часа — 40 ₾',
                'col3_en' => '3 hours — 40 ₾',
                'is_active' => true,
                'sort' => 1,
            ],
            [
                'name_ru' => 'Дуэт',
                'name_en' => 'Duet',
                'col1_ru' => '1 час — 30 ₾',
                'col1_en' => '1 hour — 30 ₾',
                'col2_ru' => '2 часа — 40 ₾',
                'col2_en' => '2 hours — 40 ₾',
                'col3_ru' => '3 часа — 50 ₾',
                'col3_en' => '3 hours — 50 ₾',
                'is_active' => true,
                'sort' => 2,
            ],
            [
                'name_ru' => 'Группа',
                'name_en' => 'Band',
                'col1_ru' => '1 час — 50 ₾',
                'col1_en' => '1 hour — 50 ₾',
                'col2_ru' => '2 часа — 80 ₾',
                'col2_en' => '2 hours — 80 ₾',
                'col3_ru' => '3 часа — 100 ₾',
                'col3_en' => '3 hours — 100 ₾',
                'is_active' => true,
                'sort' => 3,
            ],
            [
                'name_ru' => '2 ударные установки',
                'name_en' => '2 drum kits',
                'col1_ru' => '1 час — 35 ₾',
                'col1_en' => '1 hour — 35 ₾',
                'col2_ru' => '2 часа — 55 ₾',
                'col2_en' => '2 hours — 55 ₾',
                'col3_ru' => '3 часа — 70 ₾',
                'col3_en' => '3 hours — 70 ₾',
                'is_active' => true,
                'sort' => 4,
            ],
            [
                'name_ru' => 'Аренда инструментов',
                'name_en' => 'Instrument rental',
                'col1_ru' => 'Электрогитара 5 ₾ / сеанс: Ibanez (EMG 81/85)',
                'col1_en' => 'Electric guitar 5 ₾ / session: Ibanez (EMG 81/85)',
                'col2_ru' => 'Бас-гитара 5 ₾ / сеанс: Washburn',
                'col2_en' => 'Bass guitar 5 ₾ / session: Washburn',
                'col3_ru' => null,
                'col3_en' => null,
                'is_active' => true,
                'sort' => 5,
            ],
            [
                'name_ru' => 'Для барабанщика',
                'name_en' => 'For drummers',
                'col1_ru' => 'Вторая басовая педаль 5 ₾ / сеанс: Gretsh Energy',
                'col1_en' => 'Second bass pedal 5 ₾ / session: Gretsh Energy',
                'col2_ru' => 'Коврик (ударная педаль) 5 ₾ / сеанс: Millenium PD-222',
                'col2_en' => 'Kick pedal mat 5 ₾ / session: Millenium PD-222',
                'col3_ru' => null,
                'col3_en' => null,
                'is_active' => true,
                'sort' => 6,
            ],
        ];

        foreach ($prices as $price) {
            Price::updateOrCreate(
                ['name_ru' => $price['name_ru']],
                [
                    'name_en' => $price['name_en'],
                    'col1_ru' => $price['col1_ru'],
                    'col1_en' => $price['col1_en'],
                    'col2_ru' => $price['col2_ru'],
                    'col2_en' => $price['col2_en'],
                    'col3_ru' => $price['col3_ru'],
                    'col3_en' => $price['col3_en'],
                    'is_active' => $price['is_active'],
                    'sort' => $price['sort'],
                ]
            );
        }
    }
}
