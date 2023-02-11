<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table = 'product_image';

    protected $fillable = ['product_id','image_id'];

    protected $hidden = ['created_at', 'updated_at'];
}
