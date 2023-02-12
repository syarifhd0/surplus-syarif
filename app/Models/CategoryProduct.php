<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{   
    protected $table = 'category_product';

    protected $fillable = ['category_id','product_id'];

    protected $hidden = ['created_at', 'updated_at'];

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }
}
