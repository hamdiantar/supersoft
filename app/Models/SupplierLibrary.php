<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierLibrary extends Model
{
    protected $fillable = ['supplier_id','file_name', 'extension', 'name'];

    protected $table = 'supplier_libraries';

    public function supplier () {

        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
