<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierContact extends Model
{
    protected $fillable = ['supplier_id','phone_1','phone_2','address', 'name'];

    protected $table = 'supplier_contacts';

    public function supplier() {

        return $this->belongsTo(Supplier::class, 'supplier_id')->withTrashed();
    }
}
