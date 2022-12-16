<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Controllers\PiecemealListController;

class ProductController extends PiecemealListController
{
    protected $model = Product::class;

    protected $orderByColumn = 'id';

    protected $orderByDirection = 'asc';

    protected function withRelationships()
    {
        return [
            'photo'
        ];
    }
}
