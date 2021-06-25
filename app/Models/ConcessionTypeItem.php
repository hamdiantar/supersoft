<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConcessionTypeItem extends Model
{
    protected $fillable = ['name','model','type'];

    protected $table = 'concession_type_items';
}
