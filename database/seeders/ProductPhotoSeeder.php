<?php

namespace Database\Seeders;

use App\Models\ProductPhoto;
use Illuminate\Database\Seeder;

class ProductPhotoSeeder extends Seeder
{
    protected $data = [
        ['url' => '/images/k1vw70y4l8tjj1640tmwwjpxevxun15k.jpg', 'product_id' => 1],
        ['url' => '/images/lkifn6f7fera46j9l2l9wzsj75lesuz3.jpg', 'product_id' => 2],
        ['url' => '/images/n3ulm3r0av1k2338eh5yrihq3t8xgbj4.jpg', 'product_id' => 3],
        ['url' => '/images/44r0wzb1ygd7kv9t8jxmr3mq4ihilag4.jpg', 'product_id' => 4],
        ['url' => '/images/2mtvekx9n0puqspr4o8nfg4fyafbvgu6.jpg', 'product_id' => 5],
        ['url' => '/images/sg9qx0op3w8w1xpnytcen5bc9j1jwsr1.jpg', 'product_id' => 6],
        ['url' => '/images/ptpclqh71z847kpf6oh05fwksg6ivt3u.jpg', 'product_id' => 7],
        ['url' => '/images/425pibkluonfxtfdeb8u0o2ddkf2ifi7.jpg', 'product_id' => 8],
        ['url' => '/images/0xbpq9lbu2ci7yt00tt3e3yp8ynxnrit.jpg', 'product_id' => 9],
        ['url' => '/images/s383m1acau2ftxh8sx3jq6f1f4787ul1.jpg', 'product_id' => 10],
        ['url' => '/images/c2oyd55c3nefncqde7mwu4xi4woi77yb.jpg', 'product_id' => 11],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductPhoto::insert($this->data);
    }
}
