<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    protected $data = [
        ['name' => 'Пицца «Деревенская»', 'description' => 'Начинка пиццы «Деревенская» отличается своим многообразием. В ней можно найти сразу два вида лука (красный и репчатый), бекон, свинину и свежие шампиньоны. Особую пикантность блюду придает ароматный чеснок, вкус которого призван уменьшать сыр Моцарелла.', 'price' => 686],
        ['name' => '«Чиз» пицца', 'description' => 'Тесто для пиццы, сыр п/ф, пармезан, моцарелла/российский, творожный, базилик, соус', 'price' => 686],
        ['name' => 'Пицца «Детская»', 'description' => 'Тесто для пиццы, ветчина, сервелат, сыр полутвердый, соус', 'price' => 686],
        ['name' => 'Пицца «Морская Нью»', 'description' => 'Тесто для пиццы, кальмар, креветки очищенные, крабовое мясо, помидор свежий, масло чесночное, маслины, сыр, орегано соус', 'price' => 709],
        ['name' => 'Пицца «Грибная»', 'description' => 'Тесто для пиццы, грибы шампиньоны маринованные, соус, сыр моцарелла/российский', 'price' => 686],
        ['name' => 'Пицца «Хот Дьябло»', 'description' => 'Тесто для пиццы, бекон, помидоры свежие, колбаски карпатские, перец болгарский свежий, перец халапенью, лук, соус', 'price' => 686],
        ['name' => 'Пицца «Жульен Нью»', 'description' => 'Тесто для пиццы, куриное мясо, шампиньоны консервированные, бекон, помидор, зелень, сыр тертый, соус', 'price' => 686],
        ['name' => 'Пицца «Карбонара»', 'description' => 'Тесто для пиццы, ветчина, бекон, яйцо, сыр полутвердый, соус', 'price' => 686],
        ['name' => 'Пицца «По-осетински»', 'description' => 'Тесто для пиццы, сервелат, бекон, шампиньоны консервированные, помидор, сыр полутвердый, кунжут, соус', 'price' => 709],
        ['name' => 'Пицца «Ассорти»', 'description' => 'Тесто для пиццы, сервелат, шампиньоны консервированные, филе куриное отварное, лук синий, сыр полутвердый, соус', 'price' => 686],
        ['name' => 'Пицца «Те самые половинки»', 'description' => 'Тесто для пиццы, колбаски, кабаносси, огурец маринованный, помидор, сыр, соус', 'price' => 686],
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
