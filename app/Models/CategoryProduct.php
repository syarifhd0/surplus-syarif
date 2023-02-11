<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{   
    protected $table = 'category_product';

    protected $fillable = ['category_id','product_id'];

    protected $hidden = ['created_at', 'updated_at'];
}
