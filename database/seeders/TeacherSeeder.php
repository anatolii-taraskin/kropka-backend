<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Teacher::updateOrCreate(
            ['name_ru' => 'Антон'],
            [
                'name_en' => 'Anton',
                'description_ru' => 'Индивидуальные занятия на настоящих живых барабанах. Урок 60 минут стоит 65 лари. Все ритмы играются под музыку и сопровождаются бас-гитарой. Занятия проходят на двух барабанных установках и укомплектованы фирменными тарелками, а так же двойной педалью. Пробное занятие стоит 50 лари. Для взрослых и детей с 8 лет. Так же в наличии имеются Подарочные Сертификаты на 1 и на 2 занятия — подари классное увлечение длиною в жизнь!',
                'description_en' => 'Individual lessons on real acoustic drums. A 60-minute class costs 65 GEL. Every groove is played along with music and supported by bass guitar. Lessons take place on two drum kits equipped with premium cymbals and a double pedal. A trial lesson costs 50 GEL. For adults and children from 8 years old. Gift certificates for 1 or 2 lessons are also available—share a lifelong passion!',
                'telegram_url' => null,
                'photo_path' => 'teachers/anton.jpg',
                'is_active' => true,
                'sort' => 1,
            ]
        );
    }
}
