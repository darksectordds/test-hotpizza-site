<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Controllers\ListController;
use Illuminate\Http\Request;

class ProductController extends ListController
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
