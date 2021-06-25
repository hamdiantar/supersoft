<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConcessionLibrary extends Model
{
    protected $fillable = ['name','concession_id','file_name','extension'];

    protected $table = 'concession_libraries';

    public function concession () {

        return $this->belongsTo(Concession::class, 'concession_id');
    }
}
