<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixProductPhotoExtFromJPGtoPNGSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // UPDATE `product_photo` SET `url`=REPLACE(`url`, '.jpg', '.png');
        DB::statement(" UPDATE `product_photo` SET `url`=REPLACE(`url`, '.jpg', '.png')");
    }
}
