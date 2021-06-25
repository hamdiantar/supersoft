<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartStore extends Model
{
    protected $table = 'part_store';

    protected $fillable = ['part_id' ,'store_id' ,'quantity'];
}
