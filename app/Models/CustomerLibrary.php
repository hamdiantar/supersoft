<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerLibrary extends Model
{
    protected $fillable = ['name','customer_id','file_name','extension', 'name'];

    protected $table = 'customer_libraries';

    public function customer () {

        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
