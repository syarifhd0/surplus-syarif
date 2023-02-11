<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    
    protected $table = 'category';

    protected $fillable = ['name','enable'];

    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];
}
