<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    protected $data = [
        ['name' => 'Пицца "Деревенская"', 'description' => '', 'price' => 686],
        ['name' => '"Чиз" пицца', 'description' => '', 'price' => 686],
        ['name' => 'Пицца "Детская"', 'description' => '', 'price' => 686],
        ['name' => 'Пицца "Морская Нью"', 'description' => '', 'price' => 709],
        ['name' => 'Пицца "Грибная"', 'description' => '', 'price' => 686],
        ['name' => 'Пицца "Хот Дьябло"', 'description' => '', 'price' => 686],
        ['name' => 'Пицца "Жульен Нью"', 'description' => '', 'price' => 686],
        ['name' => 'Пицца "Карбонара"', 'description' => '', 'price' => 686],
        ['name' => 'Пицца "По-осетински"', 'description' => '', 'price' => 709],
        ['name' => 'Пицца "Ассорти"', 'description' => '', 'price' => 686],
        ['name' => 'Пицца "Те самые половинки"', 'description' => '', 'price' => 686],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::insert($this->data);
    }
}
