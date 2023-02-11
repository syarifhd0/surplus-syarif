<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes;
    
    protected $table = 'image';

    protected $fillable = ['name','file','enable'];

    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];
}
