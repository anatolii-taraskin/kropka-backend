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
                'name' => 'Соло / с учеником',
                'col1' => '1 час — 20 ₾',
                'col2' => '2 часа — 30 ₾',
                'col3' => '3 часа — 40 ₾',
                'is_active' => true,
                'sort' => 1,
            ],
            [
                'name' => 'Дуэт',
                'col1' => '1 час — 30 ₾',
                'col2' => '2 часа — 40 ₾',
                'col3' => '3 часа — 50 ₾',
                'is_active' => true,
                'sort' => 2,
            ],
            [
                'name' => 'Группа',
                'col1' => '1 час — 50 ₾',
                'col2' => '2 часа — 80 ₾',
                'col3' => '3 часа — 100 ₾',
                'is_active' => true,
                'sort' => 3,
            ],
            [
                'name' => '2 ударные установки',
                'col1' => '1 час — 35 ₾',
                'col2' => '2 часа — 55 ₾',
                'col3' => '3 часа — 70 ₾',
                'is_active' => true,
                'sort' => 4,
            ],
            [
                'name' => 'Аренда инструментов',
                'col1' => 'Электрогитара 5 ₾ / сеанс: Ibanez (EMG 81/85)',
                'col2' => 'Бас-гитара 5 ₾ / сеанс: Washburn',
                'col3' => null,
                'is_active' => true,
                'sort' => 5,
            ],
            [
                'name' => 'Для барабанщика',
                'col1' => 'Вторая басовая педаль 5 ₾ / сеанс: Gretsh Energy',
                'col2' => 'Коврик (ударная педаль) 5 ₾ / сеанс: Millenium PD-222',
                'col3' => null,
                'is_active' => true,
                'sort' => 6,
            ],
        ];

        foreach ($prices as $price) {
            Price::updateOrCreate(
                ['name' => $price['name']],
                [
                    'col1' => $price['col1'],
                    'col2' => $price['col2'],
                    'col3' => $price['col3'],
                    'is_active' => $price['is_active'],
                    'sort' => $price['sort'],
                ]
            );
        }
    }
}
