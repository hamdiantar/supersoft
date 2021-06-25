<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartLibrary extends Model
{
    protected $fillable = ['part_id', 'file_name', 'extension', 'name'];

    protected $table = 'part_libraries';

    public function part()
    {
        return $this->belongsTo(Part::class, 'part_id');
    }
}
