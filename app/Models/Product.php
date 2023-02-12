<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    
    protected $table = 'product';

    protected $fillable = ['name','description','enable'];

    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];

    public function category_product(){
        return $this->hasMany(CategoryProduct::class,'product_id');
    }
}
